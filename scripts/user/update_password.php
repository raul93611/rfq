<?php
header('Content-Type: application/json');

$errors = null;

$logged_in_user = $_SESSION['user'] ?? null;
$target_id = $_POST['id_user'] ?? null;

if (!$logged_in_user || (!$logged_in_user->is_admin() && $logged_in_user->obtener_id() != $target_id)) {
  echo json_encode(['errors' => 'Unauthorized.']);
  exit;
}

try {
  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Update the user password
  $password_hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
  RepositorioUsuario::update_password($conexion, $password_hashed, $target_id);

  // Close the database connection
  Conexion::cerrar_conexion();
} catch (Exception $e) {
  // Capture any exceptions and set the errors variable
  $errors = 'An error occurred: ' . $e->getMessage();

  // Ensure the connection is closed in case of an error
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }
}

echo json_encode(array(
  'errors' => $errors
));
