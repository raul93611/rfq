<?php
header('Content-Type: application/json');
try {
  // Retrieve POST parameters
  $start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
  $length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
  $search = $_POST['search']['value'] ?? '';
  $sort_column_index = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 0;
  $sort_direction = $_POST['order'][0]['dir'] ?? 'asc';
  $searchTerm = $_POST['searchTerm'] ?? '';
  $advanced = ($_POST['advanced'] ?? '') === '1';

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  if ($advanced) {
    // Advanced mode: keyword optional, filters AND-combined.
    $filters = [
      'statuses' => (array)($_POST['statuses'] ?? []),
      'user' => $_POST['f_user'] ?? '',
      'bid_type' => $_POST['f_bid_type'] ?? '',
      'contract_type' => $_POST['f_contract_type'] ?? '',
      'date_field' => $_POST['f_date_field'] ?? 'created',
      'date_from' => $_POST['f_date_from'] ?? '',
      'date_to' => $_POST['f_date_to'] ?? '',
      'price_min' => $_POST['f_price_min'] ?? '',
      'price_max' => $_POST['f_price_max'] ?? '',
      'client' => $_POST['f_client'] ?? '',
      'state' => $_POST['f_state'] ?? '',
    ];
    $quotes = RepositorioRfq::getAdvancedSearchedQuotes($conexion, $start, $length, $sort_column_index, $sort_direction, $searchTerm, $filters);
    $total_records = RepositorioRfq::getAdvancedSearchedQuotesCount($conexion, $searchTerm, $filters);
  } else {
    $quotes = RepositorioRfq::getSearchedQuotesByChannel($conexion, $start, $length, $search, $sort_column_index, $sort_direction, $searchTerm);
    $total_records = RepositorioRfq::getTotalSearchedQuotesByChannelCount($conexion, $searchTerm);
  }
  $total_filtered_records = $total_records; // Assuming filtered and total counts are the same

  // Close database connection
  Conexion::cerrar_conexion();

  // Prepare response data
  $response = [
    "draw" => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,
    "recordsTotal" => $total_records,
    "recordsFiltered" => $total_filtered_records,
    "data" => $quotes
  ];
} catch (Exception $e) {
  // Handle exceptions and include error message in the response
  $response = [
    "error" => "Error fetching data: " . $e->getMessage()
  ];
} finally {
  // Ensure the database connection is closed
  Conexion::cerrar_conexion();
}

// Output the response as JSON
echo json_encode($response);
