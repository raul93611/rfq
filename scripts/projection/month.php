<?php
header('Content-Type: application/json');

try {
  // Validate and sanitize inputs
  $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
  $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
  $search = isset($_POST['search']['value']) ? htmlspecialchars(trim($_POST['search']['value'])) : '';
  $sort_column_index = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
  $sort_direction = isset($_POST['order'][0]['dir']) && in_array($_POST['order'][0]['dir'], ['asc', 'desc']) ? $_POST['order'][0]['dir'] : 'asc';
  $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
  $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;

  if ($id <= 0) {
    throw new Exception("Invalid ID");
  }

  // Open database connection
  Conexion::abrir_conexion();

  // Fetch data
  $items = YearlyProjectionRepository::getMonth(Conexion::obtener_conexion(), $start, $length, $search, $sort_column_index, $sort_direction, $id);
  $total_records = YearlyProjectionRepository::getTotalMonthCount(Conexion::obtener_conexion(), $id);
  $total_filtered_records = YearlyProjectionRepository::getTotalFilteredMonthCount(Conexion::obtener_conexion(), $search, $id);

  // Close database connection
  Conexion::cerrar_conexion();

  // Prepare response
  $response = array(
    "draw" => $draw,
    "recordsTotal" => $total_records,
    "recordsFiltered" => $total_filtered_records,
    "data" => $items
  );

  echo json_encode($response);
} catch (Exception $e) {
  // Handle exceptions and prepare error response
  $error_response = array(
    "error" => $e->getMessage()
  );
  echo json_encode($error_response);
  if (Conexion::obtener_conexion()) {
    Conexion::cerrar_conexion();
  }
}
