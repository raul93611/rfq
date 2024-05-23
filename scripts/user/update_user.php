<?php
header('Content-Type: application/json');

$errors = null;
try {
  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Check if the username exists for another user
  if (!RepositorioUsuario::usernameExistMoreThan2($conexion, $_POST["username"], $_POST["id_user"])) {
    // Edit the user details
    $edited_user = RepositorioUsuario::edit_user(
      $conexion,
      $_POST['username'],
      $_POST['nombres'],
      $_POST['apellidos'],
      implode(',', $_POST['cargo']),
      $_POST['email'],
      $_POST['id_user']
    );
  } else {
    $errors = 'Username is already taken';
  }

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

// Output the result as JSON
echo json_encode(array(
  'errors' => $errors
));
