<?php
header('Content-Type: application/json');

$start = $_POST['start'];
$length = $_POST['length'];
$search = $_POST['search']['value'];
$sort_column_index = $_POST['order'][0]['column'];
$sort_direction = $_POST['order'][0]['dir'];

Conexion::abrir_conexion();
$quotes = Report::getAwardReport(
  Conexion::obtener_conexion(),
  $start,
  $length,
  $search,
  $sort_column_index,
  $sort_direction,
  $_POST['type'],
  $_POST['quarter'],
  $_POST['month'],
  $_POST['year']
);
$total_records = Report::getAwardReportCount(
  Conexion::obtener_conexion(),
  $_POST['type'],
  $_POST['quarter'],
  $_POST['month'],
  $_POST['year']
);
$total_filtered_records = Report::getFilteredAwardReportCount(
  Conexion::obtener_conexion(),
  $search,
  $_POST['type'],
  $_POST['quarter'],
  $_POST['month'],
  $_POST['year']
);
Conexion::cerrar_conexion();

$columns = array(
  0 => 'id',
  1 => 'fecha_award',
  2 => 'contract_number',
  3 => 'email_code',
  4 => 'nombre_usuario',
  5 => 'canal',
  6 => 'type_of_bid',
  7 => 'total_cost',
  8 => 'total_price',
  9 => 'profit',
  10 => 'type_of_contract'
);

$response = array(
  "draw" => $_POST['draw'],
  "recordsTotal" => $total_records,
  "recordsFiltered" => $total_filtered_records,
  "data" => $quotes
);

echo json_encode($response);
