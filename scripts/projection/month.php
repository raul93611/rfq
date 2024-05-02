<?php
header('Content-Type: application/json');

$start = $_POST['start'];
$length = $_POST['length'];
$search = $_POST['search']['value'];
$sort_column_index = $_POST['order'][0]['column'];
$sort_direction = $_POST['order'][0]['dir'];

Conexion::abrir_conexion();
$items = YearlyProjectionRepository::getMonth(Conexion::obtener_conexion(), $start, $length, $search, $sort_column_index, $sort_direction, $_POST["id"]);
$total_records = YearlyProjectionRepository::getTotalMonthCount(Conexion::obtener_conexion(), $_POST["id"]);
$total_filtered_records = YearlyProjectionRepository::getTotalFilteredMonthCount(Conexion::obtener_conexion(), $search, $_POST["id"]);
$allItems = YearlyProjectionRepository::getMonth(Conexion::obtener_conexion(), 0, $total_records, null, 'id_quote', null, $_POST["id"]);
Conexion::cerrar_conexion();

$contractCounts = [];

foreach ($allItems as $item) {
  $contractType = $item['type_of_contract'];
  $contractCounts[$contractType] = ($contractCounts[$contractType] ?? 0) + 1;
}

$response = array(
  "draw" => $_POST['draw'],
  "recordsTotal" => $total_records,
  "recordsFiltered" => $total_filtered_records,
  "data" => $items,
  "contractCounts" => $contractCounts
);

echo json_encode($response);
