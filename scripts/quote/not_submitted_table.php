<?php
header('Content-Type: application/json');

// Function to sanitize input
function sanitize_input($input) {
  return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Validate and sanitize inputs
$start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
$length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
$search = isset($_POST['search']['value']) ? sanitize_input($_POST['search']['value']) : '';
$sort_column_index = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 0;
$sort_direction = isset($_POST['order'][0]['dir']) && in_array($_POST['order'][0]['dir'], ['asc', 'desc']) ? $_POST['order'][0]['dir'] : 'asc';
$draw = isset($_POST['draw']) ? (int)$_POST['draw'] : 0;

$response = [];

try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Get the quotes based on the provided parameters
  $quotes = RepositorioRfq::getNotSubmittedQuotes($conexion, $start, $length, $search, $sort_column_index, $sort_direction);
  $total_records = RepositorioRfq::getTotalNotSubmittedQuotesCount($conexion);
  $total_filtered_records = RepositorioRfq::getTotalFilteredNotSubmittedQuotesCount($conexion, $search);

  // Close database connection
  Conexion::cerrar_conexion();

  // Format the response
  $response = [
    "draw" => $draw,
    "recordsTotal" => $total_records,
    "recordsFiltered" => $total_filtered_records,
    "data" => $quotes
  ];
} catch (Exception $e) {
  // Handle exceptions and send error response
  $response = ['error' => 'An error occurred while processing your request'];
}

// Send the JSON response
echo json_encode($response);
