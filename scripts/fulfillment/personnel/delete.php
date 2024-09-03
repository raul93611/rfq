<?php
header('Content-Type: application/json');

// Initialize response array
$response = [
  'status' => 'error',
  'message' => 'An error occurred.'
];

try {
  // Check if 'id' is provided and is valid
  if (empty($_POST['id']) || !is_numeric($_POST['id'])) {
    throw new Exception('Invalid or missing ID.');
  }

  // Sanitize the input
  $id = (int)$_POST['id'];

  // Open the database connection
  Conexion::abrir_conexion();

  // Delete the record
  $result = PersonnelRepository::delete(Conexion::obtener_conexion(), $id);

  // Check if the deletion was successful
  if ($result) {
    $response['status'] = 'success';
    $response['message'] = 'Record deleted successfully.';
  } else {
    throw new Exception('Failed to delete record.');
  }
} catch (Exception $e) {
  // Handle any exceptions by setting the error message
  $response['message'] = $e->getMessage();
} finally {
  // Close the database connection
  Conexion::cerrar_conexion();

  // Return the JSON response
  echo json_encode($response);
  exit;
}
