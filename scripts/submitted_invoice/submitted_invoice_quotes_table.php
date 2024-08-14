<?php
header('Content-Type: application/json');

try {
  // Retrieve and validate POST data
  $start = filter_input(INPUT_POST, 'start', FILTER_VALIDATE_INT);
  $length = filter_input(INPUT_POST, 'length', FILTER_VALIDATE_INT);
  $search = filter_input(INPUT_POST, 'search', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
  $sort = filter_input(INPUT_POST, 'order', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
  $draw = filter_input(INPUT_POST, 'draw', FILTER_VALIDATE_INT);

  if ($start === false || $length === false || !$search || !$sort || $draw === false) {
    throw new Exception("Invalid input data.");
  }

  $search_value = $search['value'] ?? '';
  $sort_column_index = $sort[0]['column'] ?? null;
  $sort_direction = $sort[0]['dir'] ?? null;

  if ($sort_column_index === null || $sort_direction === null) {
    throw new Exception("Sorting parameters are missing.");
  }

  // Open connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch data
  $quotes = RepositorioRfq::getSubmittedInvoiceQuotes($conexion, $start, $length, $search_value, $sort_column_index, $sort_direction);
  $total_records = RepositorioRfq::getTotalSubmittedInvoiceQuotesCount($conexion);
  $total_filtered_records = RepositorioRfq::getTotalFilteredSubmittedInvoiceQuotesCount($conexion, $search_value);

  // Prepare response
  $response = array(
    "draw" => $draw,
    "recordsTotal" => $total_records,
    "recordsFiltered" => $total_filtered_records,
    "data" => $quotes
  );
} catch (Exception $e) {
  // Handle exceptions
  $response = array(
    "draw" => $draw ?? 0,
    "recordsTotal" => 0,
    "recordsFiltered" => 0,
    "data" => [],
    "error" => "An error occurred: " . $e->getMessage()
  );
} finally {
  // Ensure connection is closed
  Conexion::cerrar_conexion();
}

// Send JSON response
echo json_encode($response);
