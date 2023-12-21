<?php
header('Content-Type: application/json');

$start = $_POST['start'];
$length = $_POST['length'];
$search = $_POST['search']['value'];
$sort_column_index = $_POST['order'][0]['column'];
$sort_direction = $_POST['order'][0]['dir'];

Conexion::abrir_conexion();
$items = YearlyProjectionRepository::getMonthly(Conexion::obtener_conexion(), $start, $length, $search, $sort_column_index, $sort_direction, $_POST["id"]);
$total_records = YearlyProjectionRepository::getTotalMonthlyCount(Conexion::obtener_conexion(), $_POST["id"]);
$total_filtered_records = YearlyProjectionRepository::getTotalFilteredMonthlyCount(Conexion::obtener_conexion(), $search, $_POST["id"]);
Conexion::cerrar_conexion();

$response = array(
  "draw" => $_POST['draw'],
  "recordsTotal" => $total_records,
  "recordsFiltered" => $total_filtered_records,
  "data" => $items
);

echo json_encode($response);
