<?php
header('Content-Type: application/json');

$errors = null;
try {
  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Update the user password
  $password_hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
  RepositorioUsuario::update_password($conexion, $password_hashed, $_POST['id_user']);

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
