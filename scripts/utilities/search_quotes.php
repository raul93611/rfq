<?php
header('Content-Type: application/json');

$start = $_POST['start'];
$length = $_POST['length'];
$search = $_POST['search']['value'];
$sort_column_index = $_POST['order'][0]['column'];
$sort_direction = $_POST['order'][0]['dir'];

Conexion::abrir_conexion();
$quotes = RepositorioRfq::getSearchedQuotesByChannel(Conexion::obtener_conexion(), $start, $length, $search, $sort_column_index, $sort_direction, $_POST['searchTerm']);
$total_records = RepositorioRfq::getTotalSearchedQuotesByChannelCount(Conexion::obtener_conexion(), $_POST['searchTerm']);
$total_filtered_records = RepositorioRfq::getTotalSearchedQuotesByChannelCount(Conexion::obtener_conexion(), $_POST['searchTerm']);
Conexion::cerrar_conexion();

$response = array(
  "draw" => $_POST['draw'],
  "recordsTotal" => $total_records,
  "recordsFiltered" => $total_filtered_records,
  "data" => $quotes
);

echo json_encode($response);
