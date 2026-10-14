<?php
/**
 * Per-file "Share with partner" toggle (Documents drawer). POST file, shared (0|1).
 * The server is the gate: generated PDFs, partner-origin files and unshared quotes are refused
 * here regardless of what the UI offered. $id_rfq is set by index.php.
 */
header('Content-Type: application/json');

if (!ControlSesion::sesion_iniciada()) {
  http_response_code(403);
  echo json_encode(['success' => false, 'message' => 'Please sign in again.']);
  return;
}
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') !== 'XMLHttpRequest') {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Bad request.']);
  return;
}

$file = (string)($_POST['file'] ?? '');
$share = !empty($_POST['shared']);
try {
  Conexion::abrir_conexion();
  $c = Conexion::obtener_conexion();
  $ok = PartnerDocumentRepository::setShared($c, (int)$id_rfq, $file, $share);
  if ($ok && !$share) {
    $u = $_SESSION['user'];
    AuditTrailRepository::partner_event($c, 'partner_removed',
      '<b>Removed from partner</b> · ' . htmlspecialchars($file, ENT_QUOTES, 'UTF-8') . ' · file unshared',
      (int)$id_rfq, $u->obtener_nombre_usuario(), $u->obtener_id());
  }
  Conexion::cerrar_conexion();
  if (!$ok) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'This file cannot be shared. Generated documents are internal only, and sharing must be on for the quote.']);
    return;
  }
  echo json_encode(['success' => true, 'file' => $file, 'shared' => $share]);
} catch (Throwable $e) {
  error_log('partner_share_file failed: ' . $e->getMessage());
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Could not update sharing. Please try again.']);
}
