<?php
header('Content-Type: application/json');

try {
  // Validate and sanitize input
  $start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
  $length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
  $search = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';
  $sort_column_index = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 0;
  $sort_direction = isset($_POST['order'][0]['dir']) && in_array($_POST['order'][0]['dir'], ['asc', 'desc']) ? $_POST['order'][0]['dir'] : 'asc';

  // Open database connection
  Conexion::abrir_conexion();
  $connection = Conexion::obtener_conexion();

  // Fetch data
  $items = PersonnelRepository::getPersonnel($connection, $start, $length, $search, $sort_column_index, $sort_direction);
  $total_records = PersonnelRepository::getTotalPersonnelCount($connection);
  $total_filtered_records = PersonnelRepository::getTotalFilteredPersonnelCount($connection, $search);

  // Close database connection
  Conexion::cerrar_conexion();

  // Prepare response
  $response = [
    "draw" => (int)$_POST['draw'],
    "recordsTotal" => $total_records,
    "recordsFiltered" => $total_filtered_records,
    "data" => $items
  ];

  // Return JSON response
  echo json_encode($response);
} catch (Exception $e) {
  // Handle any exceptions and return an error response
  echo json_encode([
    "error" => $e->getMessage()
  ]);
  http_response_code(500); // Internal Server Error
} finally {
  if (isset($connection)) {
    Conexion::cerrar_conexion();
  }
}
