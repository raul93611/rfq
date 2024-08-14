<?php
header('Content-Type: application/json');

try {
  // Validate and sanitize input
  if (!isset($_POST['name']) || empty(trim($_POST['name']))) {
    throw new Exception('Invalid or missing name.');
  }

  $name = trim($_POST['name']);

  // Open the database connection
  Conexion::abrir_conexion();

  // Create a new TypeOfProject object
  $typeOfProject = new TypeOfProject('', $name);

  // Save the type of project to the database
  TypeOfProjectRepository::save(Conexion::obtener_conexion(), $typeOfProject);

  // Close the database connection
  Conexion::cerrar_conexion();

  // Send a success response
  echo json_encode(array(
    'data' => 'success'
  ));
} catch (Exception $e) {
  // Ensure the database connection is closed in case of an error
  Conexion::cerrar_conexion();

  // Send an error response
  echo json_encode(array(
    'data' => 'error',
    'message' => $e->getMessage()
  ));
}
