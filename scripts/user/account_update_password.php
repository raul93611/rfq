<?php
if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  echo json_encode(['ok' => false, 'error' => 'Unauthorized']);
  exit;
}

header('Content-Type: application/json');

$current  = $_POST['current_password'] ?? '';
$new_pass = $_POST['new_password'] ?? '';
$confirm  = $_POST['confirm_password'] ?? '';
$id_user  = $_SESSION['user']->obtener_id();

if (!$current || !$new_pass || !$confirm) {
  echo json_encode(['ok' => false, 'error' => 'All fields are required.']);
  exit;
}

if ($new_pass !== $confirm) {
  echo json_encode(['ok' => false, 'error' => 'New passwords do not match.']);
  exit;
}

if (strlen($new_pass) < 8) {
  echo json_encode(['ok' => false, 'error' => 'Password must be at least 8 characters.']);
  exit;
}

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

$user_row = RepositorioUsuario::obtener_usuario_por_id($conexion, $id_user);
if (!$user_row || !password_verify($current, $user_row->obtener_password())) {
  Conexion::cerrar_conexion();
  echo json_encode(['ok' => false, 'error' => 'Current password is incorrect.']);
  exit;
}

RepositorioUsuario::update_password($conexion, password_hash($new_pass, PASSWORD_BCRYPT), $id_user);
Conexion::cerrar_conexion();

echo json_encode(['ok' => true]);
