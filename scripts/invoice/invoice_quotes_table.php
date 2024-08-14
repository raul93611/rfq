<?php
header('Content-Type: application/json');

$start = $_POST['start'];
$length = $_POST['length'];
$search = $_POST['search']['value'];
$sort_column_index = $_POST['order'][0]['column'];
$sort_direction = $_POST['order'][0]['dir'];

try {
  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch the data
  $quotes = RepositorioRfq::getInvoiceQuotes($conexion, $start, $length, $search, $sort_column_index, $sort_direction);
  $total_records = RepositorioRfq::getTotalInvoiceQuotesCount($conexion);
  $total_filtered_records = RepositorioRfq::getTotalFilteredInvoiceQuotesCount($conexion, $search);

  // Prepare the response
  $response = [
    "draw" => $_POST['draw'],
    "recordsTotal" => $total_records,
    "recordsFiltered" => $total_filtered_records,
    "data" => $quotes
  ];

  // Close the database connection
  Conexion::cerrar_conexion();
} catch (Exception $e) {
  // Handle exceptions and prepare error response
  $response = [
    "draw" => $_POST['draw'],
    "recordsTotal" => 0,
    "recordsFiltered" => 0,
    "data" => [],
    "error" => 'ERROR: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
  ];

  // Ensure the database connection is closed in case of an error
  if (Conexion::obtener_conexion()) {
    Conexion::cerrar_conexion();
  }
}

// Output the JSON response
echo json_encode($response);
