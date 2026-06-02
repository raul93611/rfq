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

  if ($quote->obtener_multi_year_project() !== null) {
    Conexion::cerrar_conexion();
    echo json_encode(['success' => false, 'message' => 'Child quotes are not synced to the sheet.']);
    exit;
  }

  $usuario = RepositorioUsuario::obtener_usuario_por_id($conexion, $quote->obtener_usuario_designado());
  $designatedUsername = $usuario ? $usuario->obtener_nombre_usuario() : '';

  Conexion::cerrar_conexion();

  if ($quote->getSheetRow()) {
    // Row already exists — re-write all columns so any edited fields are reflected
    SheetSyncService::syncRow($quote->getSheetRow(), $quote, $designatedUsername);
    $sheetRow = $quote->getSheetRow();
  } else {
    // No row yet — append (duplicate-safe)
    $sheetRow = SheetSyncService::appendRow($quote, $designatedUsername);
  }

  Conexion::abrir_conexion();
  SheetSyncRepository::updateSyncStatus(Conexion::obtener_conexion(), $id_rfq, 'synced', $sheetRow);
  $syncAt = date('M j, Y \a\t g:i A');
  Conexion::cerrar_conexion();

  echo json_encode(['success' => true, 'sync_at' => $syncAt, 'sheet_row' => $sheetRow]);
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
