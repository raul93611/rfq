<?php
header('Content-Type: application/json');

try {
  // Retrieve POST parameters
  $start = $_POST['start'];
  $length = $_POST['length'];
  $search = $_POST['search']['value'];
  $sort_column_index = $_POST['order'][0]['column'];
  $sort_direction = $_POST['order'][0]['dir'];
  $draw = $_POST['draw'];

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch user data
  $users = RepositorioUsuario::getUsers($conexion, $start, $length, $search, $sort_column_index, $sort_direction);
  $total_records = RepositorioUsuario::getTotalUsersCount($conexion);
  $total_filtered_records = RepositorioUsuario::getTotalFilteredUsersCount($conexion, $search);

  // Close database connection
  Conexion::cerrar_conexion();

  // Prepare response data
  $response = [
    "draw" => intval($draw),
    "recordsTotal" => intval($total_records),
    "recordsFiltered" => intval($total_filtered_records),
    "data" => $users
  ];

  // Output the response as JSON
  echo json_encode($response);
} catch (Exception $e) {
  // Handle exceptions and include error message in the response
  echo json_encode(['error' => 'Error fetching data: ' . $e->getMessage()]);
} finally {
  // Ensure the database connection is closed
  Conexion::cerrar_conexion();
}
