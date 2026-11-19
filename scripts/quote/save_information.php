<?php
if (isset($_POST['save_information'])) {
  if (trim($_POST['name'] ?? '') === '') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Description must be filled out.', 'field' => 'name']);
    return;
  }
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
    $priorPartner = PartnerShareRepository::row($conexion, $_POST['id_rfq']); // the whole rfq row

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
    $letter_of_intent  = $_POST['letter_of_intent']  ?? null; // whitelisted in RepositorioRfq::save_information
    $submission_method = $_POST['submission_method'] ?? null;

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
      $qa,
      $letter_of_intent,
      $submission_method
    );

    // Persist description (stored raw, escaped at render)
    if (isset($_POST['name'])) {
      RepositorioRfq::update_name($conexion, $_POST['id_rfq'], trim($_POST['name']));
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

    // Re-fetch: the saved state feeds the bid-field audit (compared with the row read before the
    // save) and the client's in-place repaint of the partner badges (the save is AJAX).
    $finalQuote = RepositorioRfq::obtener_cotizacion_por_id($conexion, $_POST['id_rfq']);
    if ($priorPartner) {
      AuditTrailRepository::bid_field_events($conexion, new Rfq($priorPartner), $finalQuote, $_POST['id_rfq']);
    }
    Conexion::cerrar_conexion();

    header('Content-Type: application/json');
    echo json_encode([
      'success' => true,
      // Repaints the header badges + Solicitation # info card without a reload (js/partner_share.js).
      'partner' => $finalQuote->getPartnerSummary(),
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
