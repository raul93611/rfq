<?php
header('Content-Type: application/json');

$start = $_POST['start'];
$length = $_POST['length'];
$search = $_POST['search']['value'];
$sort_column_index = $_POST['order'][0]['column'];
$sort_direction = $_POST['order'][0]['dir'];

Conexion::abrir_conexion();
$users = RepositorioUsuario::getUsers(Conexion::obtener_conexion(), $start, $length, $search, $sort_column_index, $sort_direction);
$total_records = RepositorioUsuario::getTotalUsersCount(Conexion::obtener_conexion());
$total_filtered_records = RepositorioUsuario::getTotalFilteredUsersCount(Conexion::obtener_conexion(), $search);
Conexion::cerrar_conexion();

$response = array(
  "draw" => $_POST['draw'],
  "recordsTotal" => $total_records,
  "recordsFiltered" => $total_filtered_records,
  "data" => $users
);

echo json_encode($response);
