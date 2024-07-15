<?php
header('Content-Type: application/json');

try {
  // Open the database connection
  Conexion::abrir_conexion();

  // Validate and sanitize input
  if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    throw new Exception('Invalid or missing ID.');
  }

  $id = (int)$_POST['id'];

  // Perform the delete operation using a prepared statement
  $deleted = TypeOfProjectRepository::delete(Conexion::obtener_conexion(), $id);

  if (!$deleted) {
    throw new Exception('Failed to delete the project type.');
  }

  // Close the database connection
  Conexion::cerrar_conexion();

  // Respond with a success message
  echo json_encode(array(
    'response' => 'success',
    'message' => 'Project type deleted successfully.'
  ));
} catch (Exception $e) {
  // Ensure the database connection is closed in case of an error
  Conexion::cerrar_conexion();

  // Respond with an error message
  echo json_encode(array(
    'response' => 'error',
    'message' => $e->getMessage()
  ));
}
