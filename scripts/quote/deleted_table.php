<?php
header('Content-Type: application/json');

try {
  // Extract and sanitize POST data
  $start = filter_input(INPUT_POST, 'start', FILTER_VALIDATE_INT);
  $length = filter_input(INPUT_POST, 'length', FILTER_VALIDATE_INT);
  $search = filter_input(INPUT_POST, 'search', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY)['value'];
  $sort_order = filter_input(INPUT_POST, 'order', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
  $sort_column_index = $sort_order[0]['column'];
  $sort_direction = $sort_order[0]['dir'];
  $draw = filter_input(INPUT_POST, 'draw', FILTER_VALIDATE_INT);

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch data
  $quotes = RepositorioRfq::getDeletedQuotes($conexion, $start, $length, $search, $sort_column_index, $sort_direction);
  $total_records = RepositorioRfq::getTotalDeletedQuotesCount($conexion);
  $total_filtered_records = RepositorioRfq::getTotalFilteredDeletedQuotesCount($conexion, $search);

  // Close the database connection
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
  // Ensure the connection is closed in case of an error
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }
  // Output error message
  echo 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
  exit;
}
