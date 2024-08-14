<?php
header('Content-Type: application/json');

try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Disable the user
  $usuario_editado = RepositorioUsuario::disable_user($conexion, $_POST['id']);

  // Close the database connection
  Conexion::cerrar_conexion();

  // Check if the user was successfully disabled
  if ($usuario_editado) {
    echo json_encode(array(
      'result' => 'success',
      'message' => 'User has been disabled successfully.'
    ));
  } else {
    echo json_encode(array(
      'result' => 'error',
      'message' => 'Failed to disable the user.'
    ));
  }
} catch (Exception $e) {
  // Close the database connection in case of an error
  Conexion::cerrar_conexion();

  // Return the error message
  echo json_encode(array(
    'result' => 'error',
    'message' => 'An error occurred: ' . $e->getMessage()
  ));
}
