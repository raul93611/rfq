<?php
/**
 * JSON: toggle the current user's watch subscription on a quote.
 * POST: id_rfq, action=watch|unwatch. Returns { ok, watching }.
 * No audit trail — watching is a personal notification preference, not a content change.
 */
if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

header('Content-Type: application/json');

$id_rfq  = (int)($_POST['id_rfq'] ?? 0);
$action  = $_POST['action'] ?? '';
if ($id_rfq <= 0 || !in_array($action, ['watch', 'unwatch'], true)) {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid request']);
  exit;
}

try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();
  $id_user = $_SESSION['user']->obtener_id();

  if ($action === 'watch') {
    QuoteWatcherRepository::watch($conexion, $id_rfq, $id_user);
  } else {
    QuoteWatcherRepository::unwatch($conexion, $id_rfq, $id_user);
  }
  $watching = QuoteWatcherRepository::isWatching($conexion, $id_rfq, $id_user);
  Conexion::cerrar_conexion();

  echo json_encode(['ok' => true, 'watching' => $watching]);
} catch (Exception $e) {
  http_response_code(500);
  error_log('watch_quote error: ' . $e->getMessage());
  echo json_encode(['error' => 'Failed to update watch state']);
}
