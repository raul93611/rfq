<?php
header('Content-Type: application/json');

if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  echo json_encode(['success' => false, 'message' => 'Unauthorized']);
  exit;
}

$id_rfq = filter_input(INPUT_POST, 'id_rfq', FILTER_VALIDATE_INT);
if (!$id_rfq) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Invalid id_rfq']);
  exit;
}

try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  $quote = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
  if (!$quote) {
    Conexion::cerrar_conexion();
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Quote not found']);
    exit;
  }

  $usuario = RepositorioUsuario::obtener_usuario_por_id($conexion, $quote->obtener_usuario_designado());
  $designatedUsername = $usuario ? $usuario->obtener_nombre_usuario() : '';

  // Capture the pre-sync linkage so we can tell whether this sync establishes something new
  // (worth a timestamp bump + audit event) or is a no-op re-affirmation of an existing link.
  $priorRow    = $quote->getSheetRow();
  $priorStatus = $quote->getSheetSyncStatus();

  Conexion::cerrar_conexion();

  // Write-once: create the row if the quote isn't in the sheet, otherwise link to it
  // (writing nothing). Never overwrites an existing row.
  $result   = SheetSyncService::createOrLink($quote, $designatedUsername);
  $sheetRow = $result['row'] ?? $priorRow;
  $outcome  = $result['outcome'];

  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();
  // Manually syncing always turns the per-quote flag on, so future edits keep it represented.
  SheetSyncRepository::setSyncToSheet($conexion, $id_rfq, 1);

  // Only stamp the status / bump sheet_sync_at / log an audit event when the link is newly
  // established — re-syncing a quote that's already linked to the same row is a no-op.
  $established = $outcome !== null
    && ($outcome === 'created'
        || (string)$priorRow !== (string)$sheetRow
        || $priorStatus !== 'synced');

  if ($established) {
    SheetSyncRepository::updateSyncStatus($conexion, $id_rfq, 'synced', $sheetRow);
    if ($outcome === 'created') {
      AuditTrailRepository::sheet_row_created_audit_trail($conexion, $id_rfq);
    } else {
      AuditTrailRepository::sheet_row_linked_audit_trail($conexion, $id_rfq);
    }
    $syncAt = date('M j, Y \a\t g:i A');
  } else {
    // Keep the existing "Last synced" time; nothing changed.
    $existing = SheetSyncRepository::getSyncInfo($conexion, $id_rfq);
    $syncAt = !empty($existing['sheet_sync_at'])
      ? date('M j, Y \a\t g:i A', strtotime($existing['sheet_sync_at']))
      : date('M j, Y \a\t g:i A');
  }
  Conexion::cerrar_conexion();

  echo json_encode(['success' => true, 'sync_at' => $syncAt, 'sheet_row' => $sheetRow, 'outcome' => $outcome]);
} catch (Exception $e) {
  error_log('Sheet sync error (manual): ' . $e->getMessage());
  try {
    Conexion::abrir_conexion();
    SheetSyncRepository::updateSyncStatus(Conexion::obtener_conexion(), $id_rfq, 'failed');
    Conexion::cerrar_conexion();
  } catch (Exception $dbEx) {
    // swallow
  }
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
