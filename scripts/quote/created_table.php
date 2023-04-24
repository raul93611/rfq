<?php
header('Content-Type: application/json');

$start = $_POST['start'];
$length = $_POST['length'];
$search = $_POST['search']['value'];
$sortColumnIndex = $_POST['order'][0]['column'];
$sortDirection = $_POST['order'][0]['dir'];

Conexion::abrir_conexion();
$cotizaciones = RepositorioRfq::obtener_cotizaciones_por_canal_usuario_cargo(Conexion::obtener_conexion(), $_POST['channel']);
$total_filtered_records = RepositorioRfq::obtener_resultados_busqueda(Conexion::obtener_conexion(), $search);
Conexion::cerrar_conexion();



$columns = array(
  0 => 'id',
  1 => 'designated_user',
  2 => 'type_of_bid',
  3 => 'issue_date',
  4 => 'end_date',
  5 => 'code',
  6 => 'rfp',
  7 => 'options',
);

$response = array(
  "draw" => $_POST['draw'],
  "recordsTotal" => count($cotizaciones),
  "recordsFiltered" => count($total_filtered_records),
  "data" => []
);

// Return JSON-encoded response
echo json_encode($response);
