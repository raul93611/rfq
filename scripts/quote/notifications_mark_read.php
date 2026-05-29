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

if (isset($_POST['id'])) {
  NotificationRepository::markRead($conexion, (int) $_POST['id'], $id_user);
} else {
  NotificationRepository::markAllRead($conexion, $id_user);
}

Conexion::cerrar_conexion();

echo json_encode(['ok' => true]);
