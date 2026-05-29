<?php
if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

header('Content-Type: application/json');

$id_user = $_SESSION['user']->obtener_id();

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

$count   = NotificationRepository::getUnreadCount($conexion, $id_user);
$recent  = NotificationRepository::getRecent($conexion, $id_user, 5);

Conexion::cerrar_conexion();

echo json_encode([
  'count' => $count,
  'items' => $recent,
]);
