<?php
header('Content-Type: application/json');

// Initialize response array and default status
$response = [
  'status' => 'error',
  'message' => 'An unexpected error occurred.',
  'data' => []
];

try {
  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion(); // Store the connection to reuse it

  // Fetch personnel and events data
  $personnel = PersonnelRepository::getAll($conexion);
  $events = CalendarEventRepository::getAll($conexion);

  // Close the database connection
  Conexion::cerrar_conexion();

  // Check if data was retrieved successfully
  if ($personnel === false || $events === false) {
    throw new Exception('Failed to retrieve data.');
  }

  // Update response on success
  $response['status'] = 'success';
  $response['message'] = 'Data retrieved successfully.';
  $response['data'] = [
    'personnel' => $personnel,
    'events' => $events
  ];
} catch (Exception $e) {
  // Handle exceptions and update the response message
  $response['message'] = $e->getMessage();
} finally {
  // Ensure the connection is closed even if an error occurs
  if (Conexion::obtener_conexion()) {
    Conexion::cerrar_conexion();
  }

  // Return the JSON response
  echo json_encode($response);
  exit;
}
