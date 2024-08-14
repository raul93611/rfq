<?php
header('Content-Type: application/json');

$response = ['status' => 'success', 'message' => 'User enabled successfully.'];

try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Enable the user
  $usuario_editado = RepositorioUsuario::enable_user($conexion, $_POST['id']);

  // Check if the user was successfully enabled
  if (!$usuario_editado) {
    $response = ['status' => 'error', 'message' => 'Failed to enable user.'];
  }

  // Close the database connection
  Conexion::cerrar_conexion();
} catch (Exception $e) {
  // Close the database connection in case of an error
  Conexion::cerrar_conexion();

  // Set error response
  $response = ['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()];
}

// Return JSON response
echo json_encode($response);
