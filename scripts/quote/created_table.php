<?php
header('Content-Type: application/json');

$start = $_POST['start'];
$length = $_POST['length'];
$search = $_POST['search']['value'];
$sort_column_index = $_POST['order'][0]['column'];
$sort_direction = $_POST['order'][0]['dir'];

Conexion::abrir_conexion();
$quotes = RepositorioRfq::getCreatedQuotesByChannel(Conexion::obtener_conexion(), $start, $length, $search, $sort_column_index, $sort_direction, $_POST['channel']);
$total_records = RepositorioRfq::getTotalCreatedQuotesByChannelCount(Conexion::obtener_conexion(), $_POST['channel']);
$total_filtered_records = RepositorioRfq::getTotalFilteredCreatedQuotesByChannelCount(Conexion::obtener_conexion(), $_POST['channel'], $search);
Conexion::cerrar_conexion();



$columns = array(
  0 => 'id',
  1 => 'nombre_usuario',
  2 => 'type_of_bid',
  3 => 'issue_date',
  4 => 'end_date',
  5 => 'email_code',
  6 => 'rfp',
  7 => 'options'
);

$response = array(
  "draw" => $_POST['draw'],
  "recordsTotal" => $total_records,
  "recordsFiltered" => $total_filtered_records,
  "data" => $quotes
);

echo json_encode($response);