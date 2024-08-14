<?php
header('Content-Type: application/json');

try {
  // Validate and sanitize input
  if (!isset($_POST['id_provider']) || !is_numeric($_POST['id_provider'])) {
    throw new Exception('Invalid provider ID');
  }
  $id_provider = intval($_POST['id_provider']);

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Perform deletion
  ProviderListRepository::delete($conexion, $id_provider);

  // Close database connection
  Conexion::cerrar_conexion();

  // Send success response
  echo json_encode(array('result' => true));
} catch (Exception $e) {
  // Handle exceptions and send error response
  $error_response = array('result' => false, 'error' => $e->getMessage());
  echo json_encode($error_response);

  // Ensure the database connection is closed in case of an error
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }
}
