<?php
header('Content-Type: application/json');

try {
  // Validate and sanitize input
  if (!isset($_POST['name']) || !isset($_POST['id_type_of_project']) || !is_numeric($_POST['id_type_of_project'])) {
    throw new Exception('Invalid input parameters.');
  }

  $name = trim($_POST['name']);
  $id_type_of_project = (int) $_POST['id_type_of_project'];

  // Open the database connection
  Conexion::abrir_conexion();

  // Update the type of project
  $updated = TypeOfProjectRepository::update(Conexion::obtener_conexion(), $name, $id_type_of_project);

  // Check if the update was successful
  if (!$updated) {
    throw new Exception('Failed to update the type of project.');
  }

  // Close the database connection
  Conexion::cerrar_conexion();

  // Send a success response
  echo json_encode(array(
    'response' => 'success'
  ));
} catch (Exception $e) {
  // Ensure the database connection is closed in case of an error
  Conexion::cerrar_conexion();

  // Send an error response
  echo json_encode(array(
    'error' => 'An error occurred.',
    'message' => $e->getMessage()
  ));
}
