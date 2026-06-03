<?php
if (isset($_POST['save_information'])) {
  try {
    // Open database connection
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

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
        if ($updatedQuote->getSheetRow()) {
          // syncRow self-heals a stale pointer and returns the row it actually wrote.
          $writtenRow = SheetSyncService::syncRow($updatedQuote->getSheetRow(), $updatedQuote, $designatedUsername);
        } else {
          // Flagged to sync but no row yet (e.g. a prior append failed) — append (dedup-safe).
          $writtenRow = SheetSyncService::appendRow($updatedQuote, $designatedUsername);
        }
        SheetSyncRepository::updateSyncStatus($conexion, $_POST['id_rfq'], 'synced', $writtenRow);
      }
    } catch (Exception $syncEx) {
      SheetSyncRepository::updateSyncStatus($conexion, $_POST['id_rfq'], 'failed');
      error_log('Sheet sync error on information save: ' . $syncEx->getMessage());
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

    // Close database connection
    Conexion::cerrar_conexion();

    // Redirect to information page
    Redireccion::redirigir(INFORMATION . $_POST['id_rfq']);
  } catch (Exception $e) {
    // Ensure the connection is closed in case of an error
    if (isset($conexion)) {
      Conexion::cerrar_conexion();
    }

    // Handle the exception (logging, user feedback, etc.)
    error_log('Error saving information: ' . $e->getMessage());
    // Optionally, redirect to an error page or display an error message
    Redireccion::redirigir(ERROR);
  }
}
