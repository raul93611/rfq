<?php
header('Content-Type: application/json');

$success = false;

try {
  // Open the database connection
  Conexion::abrir_conexion();

  // Check if the projection exists
  $conexion = Conexion::obtener_conexion();
  if (!YearlyProjectionRepository::projectionExists($conexion)) {
    // Save the projection if it doesn't exist
    YearlyProjectionRepository::save($conexion);
    $success = true;
  }
} catch (Exception $e) {
  // Handle the exception (you may log the error message or take other actions)
  echo json_encode(array('data' => $e->getMessage()));
} finally {
  // Ensure the database connection is closed
  Conexion::cerrar_conexion();
}

// Return the JSON response
echo json_encode(array('data' => $success));
