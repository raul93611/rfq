<?php
header('Content-Type: application/json');

function sanitize($data) {
  return strip_tags(trim($data));
}

$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$search = isset($_POST['search']['value']) ? sanitize($_POST['search']['value']) : '';
$sort_column_index = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
$sort_direction = isset($_POST['order'][0]['dir']) ? sanitize($_POST['order'][0]['dir']) : 'desc';
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;

try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  $quotes = RepositorioRfq::getNoAwardQuotes($conexion, $start, $length, $search, $sort_column_index, $sort_direction);
  $total_records = RepositorioRfq::getTotalNoAwardQuotesCount($conexion);
  $total_filtered_records = RepositorioRfq::getTotalFilteredNoAwardQuotesCount($conexion, $search);

  Conexion::cerrar_conexion();

  echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $total_records,
    "recordsFiltered" => $total_filtered_records,
    "data" => $quotes,
  ]);
} catch (Exception $e) {
  echo json_encode(['error' => $e->getMessage()]);
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }
  exit;
}
