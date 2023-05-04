<?php
header('Content-Type: application/json');

$start = $_POST['start'];
$length = $_POST['length'];
$search = $_POST['search']['value'];
$sort_column_index = $_POST['order'][0]['column'];
$sort_direction = $_POST['order'][0]['dir'];

Conexion::abrir_conexion();
$quotes = RepositorioRfq::getNoBidQuotes(Conexion::obtener_conexion(), $start, $length, $search, $sort_column_index, $sort_direction);
$total_records = RepositorioRfq::getTotalNoBidQuotesCount(Conexion::obtener_conexion());
$total_filtered_records = RepositorioRfq::getTotalFilteredNoBidQuotesCount(Conexion::obtener_conexion(), $search);
Conexion::cerrar_conexion();

$columns = array(
  0 => 'id',
  1 => 'nombre_usuario',
  2 => 'email_code',
  3 => 'type_of_bid',
  4 => 'comments',
  5 => 'options'
);

$response = array(
  "draw" => $_POST['draw'],
  "recordsTotal" => $total_records,
  "recordsFiltered" => $total_filtered_records,
  "data" => $quotes
);

echo json_encode($response);
