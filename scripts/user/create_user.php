<?php
header('Content-Type: application/json');

// Initialize the errors variable
$errors = null;

try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Check if the username already exists
  if (RepositorioUsuario::nombre_usuario_existe($conexion, $_POST['username'])) {
    $errors = 'Username is already taken';
  }
  // Check if the email already exists
  else if (RepositorioUsuario::email_existe($conexion, $_POST['email'])) {
    $errors = 'Email is already taken';
  }
  // If both checks pass, insert the new user
  else {
    $nuevo_usuario = new Usuario(
      '',
      $_POST['username'],
      password_hash($_POST['password'], PASSWORD_DEFAULT),
      $_POST['nombres'],
      $_POST['apellidos'],
      implode(',', $_POST['cargo']),
      $_POST['email'],
      0,
      ''
    );
    RepositorioUsuario::insertar_usuario($conexion, $nuevo_usuario);
  }

  // Close the database connection
  Conexion::cerrar_conexion();
} catch (Exception $e) {
  // Handle any exceptions that may occur
  $errors = 'An error occurred: ' . $e->getMessage();
  Conexion::cerrar_conexion();
}

// Return the response as JSON
echo json_encode(array(
  'errors' => $errors
));
