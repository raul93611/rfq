<?php
header('Content-Type: application/json');

$start = $_POST['start'];
$length = $_POST['length'];
$search = $_POST['search']['value'];
$sort_column_index = $_POST['order'][0]['column'];
$sort_direction = $_POST['order'][0]['dir'];

Conexion::abrir_conexion();
$tasks = TaskRepository::getDoneTasks(Conexion::obtener_conexion(), $start, $length, $search, $sort_column_index, $sort_direction);
$total_records = TaskRepository::getTotalDoneTasksCount(Conexion::obtener_conexion());
$total_filtered_records = TaskRepository::getTotalFilteredDoneTasksCount(Conexion::obtener_conexion(), $search);
Conexion::cerrar_conexion();

$columns = array(
  0 => 'id',
  1 => 'title',
  2 => 'created_by',
  3 => 'assigned_to',
);

$response = array(
  "draw" => $_POST['draw'],
  "recordsTotal" => $total_records,
  "recordsFiltered" => $total_filtered_records,
  "data" => $tasks
);

echo json_encode($response);
?>
