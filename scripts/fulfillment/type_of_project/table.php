<?php
header('Content-Type: application/json');

try {
  // Validate and sanitize input
  if (
    !isset($_POST['start']) || !is_numeric($_POST['start']) ||
    !isset($_POST['length']) || !is_numeric($_POST['length']) ||
    !isset($_POST['search']['value']) ||
    !isset($_POST['order'][0]['column']) || !is_numeric($_POST['order'][0]['column']) ||
    !isset($_POST['order'][0]['dir'])
  ) {
    throw new Exception('Invalid input parameters.');
  }

  $start = (int) $_POST['start'];
  $length = (int) $_POST['length'];
  $search = htmlspecialchars($_POST['search']['value'], ENT_QUOTES, 'UTF-8');
  $sort_column_index = (int) $_POST['order'][0]['column'];
  $sort_direction = $_POST['order'][0]['dir'] === 'asc' ? 'ASC' : 'DESC';

  // Open the database connection
  Conexion::abrir_conexion();

  // Fetch data from the repository
  $items = TypeOfProjectRepository::getTypesOfProjects(Conexion::obtener_conexion(), $start, $length, $search, $sort_column_index, $sort_direction);
  $total_records = TypeOfProjectRepository::getTotalTypesOfProjectsCount(Conexion::obtener_conexion());
  $total_filtered_records = TypeOfProjectRepository::getTotalFilteredTypesOfProjectsCount(Conexion::obtener_conexion(), $search);

  // Close the database connection
  Conexion::cerrar_conexion();

  // Send a success response
  $response = array(
    "draw" => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,
    "recordsTotal" => $total_records,
    "recordsFiltered" => $total_filtered_records,
    "data" => $items
  );

  echo json_encode($response);
} catch (Exception $e) {
  // Ensure the database connection is closed in case of an error
  Conexion::cerrar_conexion();

  // Send an error response
  echo json_encode(array(
    'error' => 'An error occurred.',
    'message' => $e->getMessage()
  ));
}
