<?php
if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  echo json_encode(['ok' => false, 'error' => 'Unauthorized']);
  exit;
}

header('Content-Type: application/json');

$nombres   = trim($_POST['nombres'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$email     = trim($_POST['email'] ?? '');
$id_user   = $_SESSION['user']->obtener_id();

if (!$nombres || !$apellidos || !$email) {
  echo json_encode(['ok' => false, 'error' => 'All fields are required.']);
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(['ok' => false, 'error' => 'Invalid email address.']);
  exit;
}

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$ok = RepositorioUsuario::update_profile($conexion, $nombres, $apellidos, $email, $id_user);
Conexion::cerrar_conexion();

echo json_encode(['ok' => $ok]);
