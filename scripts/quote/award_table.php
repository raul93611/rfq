<?php
header('Content-Type: application/json');

// Function to sanitize input data
function sanitize($data) {
  return strip_tags(trim($data));
}

// Sanitize POST inputs
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$search = isset($_POST['search']['value']) ? sanitize($_POST['search']['value']) : '';
$sort_column_index = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
$sort_direction = isset($_POST['order'][0]['dir']) ? sanitize($_POST['order'][0]['dir']) : 'asc';
$channel = isset($_POST['channel']) ? sanitize($_POST['channel']) : '';
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;

// Check for required inputs
if ($channel === '') {
  echo json_encode(array('error' => 'Channel is required'));
  exit;
}

try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch data
  $quotes = RepositorioRfq::getAwardQuotesByChannel($conexion, $start, $length, $search, $sort_column_index, $sort_direction, $channel);
  $total_records = RepositorioRfq::getTotalAwardQuotesByChannelCount($conexion, $channel);
  $total_filtered_records = RepositorioRfq::getTotalFilteredAwardQuotesByChannelCount($conexion, $channel, $search);

  // Close database connection
  Conexion::cerrar_conexion();

  // Prepare response
  $response = array(
    "draw" => $draw,
    "recordsTotal" => $total_records,
    "recordsFiltered" => $total_filtered_records,
    "data" => $quotes,
    "search" => $search
  );

  // Send JSON response
  echo json_encode($response);
} catch (Exception $e) {
  // Error handling
  echo json_encode(array('error' => $e->getMessage()));
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }
  exit;
}
