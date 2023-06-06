<?php
header('Content-Type: application/json');

$start = $_POST['start'];
$length = $_POST['length'];
$search = $_POST['search']['value'];
$sort_column_index = $_POST['order'][0]['column'];
$sort_direction = $_POST['order'][0]['dir'];

switch ($_POST['report']) {
  case 'award':
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
    break;
  case 'submitted':
    Conexion::abrir_conexion();
    $quotes = Report::getSubmittedReport(
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
    $total_records = Report::getSubmittedReportCount(
      Conexion::obtener_conexion(),
      $_POST['type'],
      $_POST['quarter'],
      $_POST['month'],
      $_POST['year']
    );
    $total_filtered_records = Report::getFilteredSubmittedReportCount(
      Conexion::obtener_conexion(),
      $search,
      $_POST['type'],
      $_POST['quarter'],
      $_POST['month'],
      $_POST['year']
    );
    Conexion::cerrar_conexion();
    break;
  case 'submitted':
    Conexion::abrir_conexion();
    $quotes = Report::getSubmittedReport(
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
    $total_records = Report::getSubmittedReportCount(
      Conexion::obtener_conexion(),
      $_POST['type'],
      $_POST['quarter'],
      $_POST['month'],
      $_POST['year']
    );
    $total_filtered_records = Report::getFilteredSubmittedReportCount(
      Conexion::obtener_conexion(),
      $search,
      $_POST['type'],
      $_POST['quarter'],
      $_POST['month'],
      $_POST['year']
    );
    Conexion::cerrar_conexion();
    break;
  case 'fulfillment':
    Conexion::abrir_conexion();
    $quotes = Report::getFulfillmentReport(
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
    $total_records = Report::getFulfillmentReportCount(
      Conexion::obtener_conexion(),
      $_POST['type'],
      $_POST['quarter'],
      $_POST['month'],
      $_POST['year']
    );
    $total_filtered_records = Report::getFilteredFulfillmentReportCount(
      Conexion::obtener_conexion(),
      $search,
      $_POST['type'],
      $_POST['quarter'],
      $_POST['month'],
      $_POST['year']
    );
    Conexion::cerrar_conexion();
    break;
  default:

    break;
}



$response = array(
  "draw" => $_POST['draw'],
  "recordsTotal" => $total_records,
  "recordsFiltered" => $total_filtered_records,
  "data" => $quotes,
  "additional" => 'true'
);

echo json_encode($response);
