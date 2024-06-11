<?php
header('Content-Type: application/json');

try {
  // Validate and sanitize input
  $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
  $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
  $search = isset($_POST['search']['value']) ? htmlspecialchars($_POST['search']['value']) : '';
  $sort_column_index = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
  $sort_direction = isset($_POST['order'][0]['dir']) && in_array($_POST['order'][0]['dir'], ['asc', 'desc']) ? $_POST['order'][0]['dir'] : 'asc';
  $channel = isset($_POST['channel']) ? htmlspecialchars($_POST['channel']) : '';

  // Validate required parameters
  if (empty($channel)) {
    throw new Exception('Channel is required');
  }

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch data
  $quotes = RepositorioRfq::getSubmittedQuotesByChannel($conexion, $start, $length, $search, $sort_column_index, $sort_direction, $channel);
  $total_records = RepositorioRfq::getTotalSubmittedQuotesByChannelCount($conexion, $channel);
  $total_filtered_records = RepositorioRfq::getTotalFilteredSubmittedQuotesByChannelCount($conexion, $channel, $search);

  // Close database connection
  Conexion::cerrar_conexion();

  // Prepare response
  $response = array(
    "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
    "recordsTotal" => $total_records,
    "recordsFiltered" => $total_filtered_records,
    "data" => $quotes
  );

  // Send JSON response
  echo json_encode($response);
} catch (Exception $e) {
  // Handle exceptions and send error response
  $error_response = array(
    "error" => $e->getMessage()
  );
  echo json_encode($error_response);
  // Ensure the database connection is closed in case of an error
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }
}
