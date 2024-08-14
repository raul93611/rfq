<?php
header('Content-Type: application/json');

$start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
$length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
$search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
$sort_column_index = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 0;
$sort_direction = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';

Conexion::abrir_conexion();
$connection = Conexion::obtener_conexion();

$quotes = RepositorioRfq::getFulfillmentQuotes($connection, $start, $length, $search, $sort_column_index, $sort_direction);
$total_records = RepositorioRfq::getTotalFulfillmentQuotesCount($connection);
$total_filtered_records = RepositorioRfq::getTotalFilteredFulfillmentQuotesCount($connection, $search);

Conexion::cerrar_conexion();

$response = [
  "draw" => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,
  "recordsTotal" => $total_records,
  "recordsFiltered" => $total_filtered_records,
  "data" => $quotes
];

echo json_encode($response);
