<?php
header('Content-Type: application/json');

$response = []; // Initialize response array

try {
  // Validate and sanitize input
  if (empty($_POST['name']) || empty($_POST['criteria']) || empty($_POST['id_type_of_project']) || empty($_POST['id_personnel'])) {
    throw new Exception("Missing required fields.");
  }

  $name = trim($_POST['name']);
  $criteria = trim($_POST['criteria']);
  $id_type_of_project = (int)$_POST['id_type_of_project'];
  $id_personnel = (int)$_POST['id_personnel'];

  // Open database connection
  Conexion::abrir_conexion();
  $connection = Conexion::obtener_conexion();

  // Update personnel record
  $update_success = PersonnelRepository::update($connection, $name, $criteria, $id_type_of_project, $id_personnel);

  if ($update_success) {
    $response['response'] = 'success';
  } else {
    throw new Exception("Failed to update personnel.");
  }
} catch (Exception $e) {
  // Handle exceptions and return error message
  $response['response'] = 'error';
  $response['message'] = $e->getMessage();
  http_response_code(400); // Bad Request
} finally {
  if (isset($connection)) {
    Conexion::cerrar_conexion(); // Ensure the connection is closed
  }
}

// Return JSON response
echo json_encode($response);
