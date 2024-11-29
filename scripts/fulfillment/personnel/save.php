<?php
header('Content-Type: application/json');

// Initialize the response array
$response = [
  'status' => 'error',
  'message' => 'An unexpected error occurred.'
];

try {
  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion(); // Store the connection to reuse it

  // Validate and sanitize input data
  $name = isset($_POST['name']) ? trim($_POST['name']) : '';
  $criteria = isset($_POST['criteria']) ? trim($_POST['criteria']) : '';
  $id_type_of_project = isset($_POST['id_type_of_project']) ? intval($_POST['id_type_of_project']) : 0;

  if (empty($name) || empty($criteria) || $id_type_of_project <= 0) {
    throw new Exception('Invalid input data.');
  }

  // Create a new Personnel object
  $personnel = new Personnel('', $name, $criteria, $id_type_of_project);

  // Save the Personnel object
  $saveResult = PersonnelRepository::save($conexion, $personnel);

  if (!$saveResult) {
    throw new Exception('Failed to save personnel data.');
  }

  // Set a success response
  $response['status'] = 'success';
  $response['message'] = 'Personnel data saved successfully.';
  $response['data'] = $saveResult;
} catch (Exception $e) {
  // Handle exceptions and set an error message
  $response['message'] = $e->getMessage();
} finally {
  // Ensure the connection is closed
  if ($conexion) {
    Conexion::cerrar_conexion();
  }

  // Return the JSON response
  echo json_encode($response);
  exit;
}
