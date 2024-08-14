<?php
header('Content-Type: application/json');

try {
  // Validate and retrieve POST parameters
  $start = isset($_POST['start']) ? (int) $_POST['start'] : 0;
  $length = isset($_POST['length']) ? (int) $_POST['length'] : 10;
  $search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
  $sort_column_index = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 0;
  $sort_direction = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';

  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Retrieve items and counts
  $items = YearlyProjectionRepository::getProjections($conexion, $start, $length, $search, $sort_column_index, $sort_direction);
  $total_records = YearlyProjectionRepository::getTotalProjectionsCount($conexion);
  $total_filtered_records = YearlyProjectionRepository::getTotalFilteredProjectionsCount($conexion, $search);
} catch (Exception $e) {
  // Handle the exception and return an error response
  error_log('Error: ' . $e->getMessage());
  $response = array(
    "draw" => isset($_POST['draw']) ? (int) $_POST['draw'] : 0,
    "recordsTotal" => 0,
    "recordsFiltered" => 0,
    "data" => [],
    "error" => 'An error occurred while processing your request.'
  );
  echo json_encode($response);
  exit;
} finally {
  // Ensure the database connection is closed
  Conexion::cerrar_conexion();
}

// Prepare the response
$response = array(
  "draw" => isset($_POST['draw']) ? (int) $_POST['draw'] : 0,
  "recordsTotal" => $total_records,
  "recordsFiltered" => $total_filtered_records,
  "data" => $items
);

// Output the JSON response
echo json_encode($response);
