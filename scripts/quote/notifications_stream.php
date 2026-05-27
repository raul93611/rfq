<?php
if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  exit;
}

$id_user = $_SESSION['user']->obtener_id();

// SSE headers
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('X-Accel-Buffering: no');

// Disable output buffering
if (ob_get_level()) ob_end_clean();

$last_count = -1;

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

$iterations = 0;
$max_iterations = 200; // ~10 minutes at 3s interval

while ($iterations < $max_iterations) {
  $count = NotificationRepository::getUnreadCount($conexion, $id_user);

  if ($count !== $last_count) {
    $recent = NotificationRepository::getRecent($conexion, $id_user, 5);
    $items = array_map(function ($n) {
      return [
        'id'       => (int) $n['id'],
        'message'  => $n['message'],
        'url'      => $n['url'],
        'is_read'  => (bool) $n['is_read'],
        'created_at' => $n['created_at'],
      ];
    }, $recent);

    $payload = json_encode(['count' => $count, 'items' => $items]);
    echo "data: {$payload}\n\n";
    flush();
    $last_count = $count;
  }

  sleep(3);
  $iterations++;

  // Check if client disconnected
  if (connection_aborted()) break;
}

Conexion::cerrar_conexion();
