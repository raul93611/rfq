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

  // Disconnect from auto-sync but KEEP the sheet_row pointer. The row stays in the sheet
  // (see the Break Sync modal copy), and retaining the pointer means a future manual
  // "Sync to Sheet" re-attaches to that exact row via syncRow() instead of taking the
  // appendRow() path — which decides append-vs-update by scanning the eventually-consistent
  // sheet and can miss the orphaned row, creating a duplicate. Auto-sync is gated on the
  // status (see save_information.php), so flipping status to 'never' is enough to stop it.
  SheetSyncRepository::updateSyncStatus($conexion, $id_rfq, 'never');

  Conexion::cerrar_conexion();

  echo json_encode(['success' => true]);
} catch (Exception $e) {
  error_log('Break sheet sync error: ' . $e->getMessage());
  try { Conexion::cerrar_conexion(); } catch (Exception $dbEx) {}
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
