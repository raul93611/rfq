<?php
header('Content-Type: application/json');
try {
  $start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
  $length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
  $sort_column_index = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 1;
  $sort_direction = $_POST['order'][0]['dir'] ?? 'desc';
  $searchTerm = $_POST['searchTerm'] ?? '';

  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  $invoices = RepositorioRfq::getSearchedInvoices($conexion, $start, $length, $sort_column_index, $sort_direction, $searchTerm);
  $total_records = RepositorioRfq::getTotalSearchedInvoicesCount($conexion, $searchTerm);

  Conexion::cerrar_conexion();

  $response = [
    "draw" => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,
    "recordsTotal" => $total_records,
    "recordsFiltered" => $total_records,
    "data" => $invoices
  ];
} catch (Exception $e) {
  $response = [
    "error" => "Error fetching data: " . $e->getMessage()
  ];
} finally {
  Conexion::cerrar_conexion();
}

echo json_encode($response);
