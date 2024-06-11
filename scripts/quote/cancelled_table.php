<?php
header('Content-Type: application/json');

// Function to sanitize input data
function sanitize($data) {
  return htmlspecialchars(strip_tags(trim($data)));
}

// Sanitize POST inputs
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$search = isset($_POST['search']['value']) ? sanitize($_POST['search']['value']) : '';
$sort_column_index = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
$sort_direction = isset($_POST['order'][0]['dir']) ? sanitize($_POST['order'][0]['dir']) : 'asc';
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;

try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch data
  $quotes = RepositorioRfq::getCancelledQuotes($conexion, $start, $length, $search, $sort_column_index, $sort_direction);
  $total_records = RepositorioRfq::getTotalCancelledQuotesCount($conexion);
  $total_filtered_records = RepositorioRfq::getTotalFilteredCancelledQuotesCount($conexion, $search);

  // Close database connection
  Conexion::cerrar_conexion();

  // Prepare response
  $response = array(
    "draw" => $draw,
    "recordsTotal" => $total_records,
    "recordsFiltered" => $total_filtered_records,
    "data" => $quotes
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
