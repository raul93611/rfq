<?php
if (isset($_POST['save_information'])) {
  if (empty($_POST['internal_due_date'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Internal Due Date must be filled out.']);
    return;
  }
  if (empty($_POST['end_date'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'End Date must be filled out.']);
    return;
  }
  try {
    // Open database connection
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    // Partner Opportunity Sync: Solicitation # is required only while sharing is on, and its
    // normalized key must be unique among live quotes. Checked before anything is written.
    $share_with_partner = isset($_POST['share_with_partner']);
    $solicitation_number = trim($_POST['solicitation_number'] ?? '');
    $partnerError = PartnerShareRepository::validateSolicitation($conexion, $share_with_partner, $solicitation_number, $_POST['id_rfq']);
    if ($partnerError !== null) {
      Conexion::cerrar_conexion();
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => $partnerError, 'field' => 'solicitation_number']);
      return;
    }
    $priorPartner = PartnerShareRepository::row($conexion, $_POST['id_rfq']);

    // Prepare data
    $fecha_completado = !empty($_POST['completed_date']) ? $_POST['completed_date'] : null;
    $expiration_date = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null;
    $usuario = RepositorioUsuario::obtener_usuario_por_nombre_usuario($conexion, $_POST['usuario_designado']);
    $usuario_designado = $usuario->obtener_id();

    // Prepare bid requirement fields
    $site_visit        = isset($_POST['site_visit'])  && $_POST['site_visit']  !== '' ? (int)$_POST['site_visit']  : null;
    $resumes           = isset($_POST['resumes'])     && $_POST['resumes']     !== '' ? (int)$_POST['resumes']     : null;
    $qa_deadline       = !empty($_POST['qa_deadline'])       ? $_POST['qa_deadline']       : null;
    $internal_due_date = !empty($_POST['internal_due_date']) ? $_POST['internal_due_date'] : null;
    $qa                = isset($_POST['qa'])          && $_POST['qa']          !== '' ? (int)$_POST['qa']          : null;

    // Save information
    RepositorioRfq::save_information(
      $conexion,
      $expiration_date,
      $fecha_completado,
      htmlspecialchars($_POST['address']),
      $_POST['ship_via'],
      $_POST['type_of_bid'],
      $_POST['issue_date'],
      $_POST['end_date'],
      $usuario_designado,
      $_POST['email_code'],
      $_POST['canal'],
      htmlspecialchars($_POST['ship_to']),
      $_POST['comments'],
      $_POST["reference_url"],
      htmlspecialchars($_POST['priority_level']),
      $_POST['id_rfq'],
      $site_visit,
      $resumes,
      $qa_deadline,
      $internal_due_date,
      $qa
    );

    // Persist description
    if (isset($_POST['name'])) {
      SheetSyncRepository::updateNameAndResetSync($conexion, $_POST['id_rfq'], trim($_POST['name']));
    }

    // Auto-sync to the SharePoint sheet whenever this quote is flagged to sync.
    // The sync_to_sheet flag is the sole gate — bid type and master-link no longer matter.
    // "Break Sync" sets the flag to 0 (keeping sheet_row), so edits then stop pushing.
    try {
      $updatedQuote = RepositorioRfq::obtener_cotizacion_por_id($conexion, $_POST['id_rfq']);
      if ($updatedQuote && (int)$updatedQuote->getSyncToSheet() === 1) {
        $designatedUsername = $_POST['usuario_designado'];
        $priorRow    = $updatedQuote->getSheetRow();
        $priorStatus = $updatedQuote->getSheetSyncStatus();

        // Write-once: create the row if this quote isn't in the sheet, otherwise link to the
        // existing row and write NOTHING. An ordinary edit of an already-linked quote makes
        // zero Graph writes and leaves every cell untouched.
        $result     = SheetSyncService::createOrLink($updatedQuote, $designatedUsername);
        $writtenRow = $result['row'] ?? $priorRow;
        $outcome    = $result['outcome'];

        // Only stamp status / bump the timestamp / log an audit event when the link is newly
        // established (created, or linked to a row this quote wasn't pointed at). A no-op edit
        // produces no Sync-tab noise and doesn't move "last synced".
        $established = $outcome !== null
          && ($outcome === 'created'
              || (string)$priorRow !== (string)$writtenRow
              || $priorStatus !== 'synced');

        if ($established) {
          SheetSyncRepository::updateSyncStatus($conexion, $_POST['id_rfq'], 'synced', $writtenRow);
          if ($outcome === 'created') {
            AuditTrailRepository::sheet_row_created_audit_trail($conexion, $_POST['id_rfq']);
          } else {
            AuditTrailRepository::sheet_row_linked_audit_trail($conexion, $_POST['id_rfq']);
          }
        }
      }
    } catch (Exception $syncEx) {
      SheetSyncRepository::updateSyncStatus($conexion, $_POST['id_rfq'], 'failed');
      error_log('Sheet sync error on information save: ' . $syncEx->getMessage());
    }

    // Partner Opportunity Sync: persist the share gate + number, log what changed, then reconcile
    // (one notice per real change). The persist is part of the save — a failure here reaches the
    // outer catch and the user sees it; the reconcile is a follow-up and must never fail a save.
    PartnerShareRepository::saveShareSettings($conexion, $_POST['id_rfq'], $share_with_partner, $solicitation_number);
    if ($priorPartner) {
      if ((string)$priorPartner['solicitation_number'] !== $solicitation_number) {
        AuditTrailRepository::create_audit_trail_modified($conexion, 'Solicitation #',
          $solicitation_number !== '' ? $solicitation_number : 'Empty',
          (string)$priorPartner['solicitation_number'] !== '' ? $priorPartner['solicitation_number'] : 'Empty', $_POST['id_rfq']);
      }
      if ((int)$priorPartner['share_with_partner'] !== ($share_with_partner ? 1 : 0)) {
        AuditTrailRepository::create_audit_trail_modified($conexion, 'Share with partner',
          $share_with_partner ? 'On' : 'Off', $share_with_partner ? 'Off' : 'On', $_POST['id_rfq']);
      }
      // Choosing a different owner is how "Needs assignment" is resolved.
      if ((int)$priorPartner['partner_needs_assignment'] === 1
          && ($_POST['usuario_designado'] ?? '') !== ($_POST['designated_user_original'] ?? '')) {
        $conexion->prepare('UPDATE rfq SET partner_needs_assignment = 0 WHERE id = :id')->execute([':id' => $_POST['id_rfq']]);
      }
    }
    try {
      PartnerSyncService::reconcile($conexion, (int)$_POST['id_rfq']);
    } catch (Throwable $partnerEx) {
      error_log('Partner reconcile after information save failed: ' . $partnerEx->getMessage());
    }

    // Log information events
    AuditTrailRepository::information_events(
      $conexion,
      $_POST['type_of_bid'],
      $_POST['type_of_bid_original'],
      $_POST['issue_date'],
      $_POST['issue_date_original'],
      $_POST['end_date'],
      $_POST['end_date_original'],
      $_POST['completed_date'],
      $_POST['completed_date_original'],
      $_POST['expiration_date'],
      $_POST['expiration_date_original'],
      $_POST['ship_via'],
      $_POST['ship_via_original'],
      $_POST['email_code'],
      $_POST['email_code_original'],
      $_POST['usuario_designado'],
      $_POST['designated_user_original'],
      $_POST['canal'],
      $_POST['canal_original'],
      $_POST['ship_to'],
      $_POST['ship_to_original'],
      $_POST['comments'],
      $_POST['comments_original'],
      $_POST['id_rfq']
    );

    // Re-fetch: the sync block above can flip sheet_sync_status/sheet_row/sheet_sync_at, and
    // the on-page Sheet Sync block is static HTML rendered once at page load — with the save
    // now AJAX instead of a full-page redirect, nothing else re-renders it, so the client
    // needs fresh values here to repaint it in place (see js/sheet_sync.js's ssRepaint).
    Conexion::abrir_conexion();
    $finalQuote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
    Conexion::cerrar_conexion();

    header('Content-Type: application/json');
    echo json_encode([
      'success' => true,
      'sheetSync' => [
        'status' => $finalQuote->getSheetSyncStatus(),
        'syncAt' => $finalQuote->getSheetSyncAt() ? date('M j, Y \a\t g:i A', strtotime($finalQuote->getSheetSyncAt())) : null,
        'row'    => $finalQuote->getSheetRow(),
      ],
      // Repaints the header badges + Solicitation # info card without a reload (js/partner_share.js).
      'partner' => [
        'state'           => $finalQuote->getPartnerLinkState(),
        'syncedAt'        => $finalQuote->getPartnerSharedUpdatedAt() ? gmdate('c', strtotime($finalQuote->getPartnerSharedUpdatedAt() . ' UTC')) : null,
        'needsAssignment' => (int)$finalQuote->getPartnerNeedsAssignment() === 1,
        'solicitation'    => (string)$finalQuote->getSolicitationNumber(),
      ],
    ]);
  } catch (Exception $e) {
    // Ensure the connection is closed in case of an error
    if (isset($conexion)) {
      Conexion::cerrar_conexion();
    }

    // Handle the exception (logging, user feedback, etc.)
    error_log('Error saving information: ' . $e->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Could not save the information. Please try again.']);
  }
}
