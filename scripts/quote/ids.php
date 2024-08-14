<?php
header('Content-Type: application/json');

// Function to sanitize input
function sanitize_input($input) {
  return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Validate and sanitize the search term
$searchTerm = isset($_POST['term']) ? sanitize_input($_POST['term']) : '';

if (empty($searchTerm)) {
  echo json_encode(['error' => 'Search term is required']);
  exit;
}

// Initialize response array
$response = [];

try {
  // Open database connection
  Conexion::abrir_conexion();

  // Get the IDs based on the search term and ID_RFQ
  $ids = RepositorioRfq::getIds(Conexion::obtener_conexion(), $searchTerm, sanitize_input($_POST["id_rfq"]));

  // Close database connection
  Conexion::cerrar_conexion();

  // Format the response
  foreach ($ids as $id) {
    $response[] = [
      'id' => $id['id'],
      'text' => $id['id']
    ];
  }

  // Send the JSON response
  echo json_encode($response);
} catch (Exception $e) {
  // Handle exceptions and send error response
  echo json_encode(['error' => 'An error occurred while processing your request']);
}
