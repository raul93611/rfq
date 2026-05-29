<?php
if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  exit;
}

$id_user = $_SESSION['user']->obtener_id();

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('X-Accel-Buffering: no');

if (ob_get_level()) ob_end_clean();

// Don't hold the PHP session file open — releases lock so other tabs work
session_write_close();

// Respect user abort immediately on each flush
ignore_user_abort(false);

$last_count = -1;

try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();
} catch (Exception $e) {
  echo "data: {\"count\":0,\"items\":[]}\n\n";
  flush();
  exit;
}

// Max 60 iterations × 3s = 3 minutes, then the client auto-reconnects via EventSource
$max = 60;

for ($i = 0; $i < $max; $i++) {
  if (connection_aborted()) break;

  $count = NotificationRepository::getUnreadCount($conexion, $id_user);

  if ($count !== $last_count) {
    $recent = NotificationRepository::getRecent($conexion, $id_user, 5);
    $items  = array_map(fn($n) => [
      'id'         => (int) $n['id'],
      'message'    => $n['message'],
      'url'        => $n['url'],
      'is_read'    => (bool)(int) $n['is_read'],
      'created_at' => $n['created_at'],
    ], $recent);

    echo "data: " . json_encode(['count' => $count, 'items' => $items]) . "\n\n";
    flush();
    $last_count = $count;
  } else {
    // Heartbeat comment so connection_aborted() fires on disconnect
    echo ": heartbeat\n\n";
    flush();
  }

  if (connection_aborted()) break;
  sleep(3);
}

Conexion::cerrar_conexion();
