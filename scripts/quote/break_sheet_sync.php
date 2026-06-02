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

  SheetSyncRepository::clearSheetRow($conexion, $id_rfq);
  SheetSyncRepository::updateSyncStatus($conexion, $id_rfq, 'never');

  Conexion::cerrar_conexion();

  echo json_encode(['success' => true]);
} catch (Exception $e) {
  error_log('Break sheet sync error: ' . $e->getMessage());
  try { Conexion::cerrar_conexion(); } catch (Exception $dbEx) {}
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
