<?php
header('Content-Type: application/json');

$start = $_POST['start'];
$length = $_POST['length'];
$search = $_POST['search']['value'];
$sort_column_index = $_POST['order'][0]['column'];
$sort_direction = $_POST['order'][0]['dir'];

Conexion::abrir_conexion();
$quotes = RepositorioRfq::obtener_cotizaciones_por_canal(Conexion::obtener_conexion(), $start, $length, $search, $sort_column_index, $sort_direction, $_POST['channel']);
$total_records = RepositorioRfq::getTotalQuotesByChannelCount(Conexion::obtener_conexion(), $_POST['channel']);
$total_filtered_records = RepositorioRfq::getTotalFilteredQuotesByChannelCount(Conexion::obtener_conexion(), $_POST['channel'], $search);
Conexion::cerrar_conexion();



$columns = array(
  0 => 'id',
  1 => 'nombre_usuario',
  2 => 'type_of_bid',
  3 => 'issue_date',
  4 => 'end_date',
  5 => 'email_code'
);

$response = array(
  "draw" => $_POST['draw'],
  "recordsTotal" => $total_records,
  "recordsFiltered" => $total_filtered_records,
  "data" => $quotes
);

echo json_encode($response);
