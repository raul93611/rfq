<?php
header('Content-Type: application/json');

try {
  // Validate and sanitize input
  if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    throw new Exception('Invalid input parameters.');
  }

  $id = (int) $_POST['id'];

  // Open the database connection
  Conexion::abrir_conexion();

  // Delete the calendar event
  $deleted = CalendarEventRepository::delete(Conexion::obtener_conexion(), $id);

  // Close the database connection
  Conexion::cerrar_conexion();

  // Check if the delete operation was successful
  if (!$deleted) {
    throw new Exception('Failed to delete the calendar event.');
  }

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
