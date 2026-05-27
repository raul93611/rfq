<?php
if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

header('Content-Type: application/json');

$id_user    = $_SESSION['user']->obtener_id();
$notif_inapp = isset($_POST['notif_inapp']) ? (int)(bool)$_POST['notif_inapp'] : 0;
$notif_email = isset($_POST['notif_email']) ? (int)(bool)$_POST['notif_email'] : 0;

Conexion::abrir_conexion();
RepositorioUsuario::save_notif_prefs(Conexion::obtener_conexion(), $id_user, $notif_inapp, $notif_email);
Conexion::cerrar_conexion();

echo json_encode(['ok' => true]);
