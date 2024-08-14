<?php
header('Content-Type: application/json');

try {
  // Validate the provider data
  $provider = ProviderListRepository::validate_provider($_POST);

  if (!$provider) {
    // If validation fails, set result to false
    echo json_encode(['result' => false]);
    exit;
  }

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Insert the provider data into the database
  ProviderListRepository::insert($conexion, $provider);

  // Close the database connection
  Conexion::cerrar_conexion();

  // Return a successful response
  echo json_encode(['result' => true]);
} catch (Exception $e) {
  // Close the database connection in case of an error
  Conexion::cerrar_conexion();

  // Output the error message as JSON
  echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
  exit;
}
