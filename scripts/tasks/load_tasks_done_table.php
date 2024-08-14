<?php
header('Content-Type: application/json');

try {
  // Retrieve POST data safely
  $start = filter_input(INPUT_POST, 'start', FILTER_VALIDATE_INT);
  $length = filter_input(INPUT_POST, 'length', FILTER_VALIDATE_INT);
  $search = filter_input(INPUT_POST, 'search', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY)['value'] ?? '';
  $order = filter_input(INPUT_POST, 'order', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY)[0] ?? [];
  $sort_column_index = $order['column'] ?? 0;
  $sort_direction = $order['dir'] ?? 'asc';
  $draw = filter_input(INPUT_POST, 'draw', FILTER_VALIDATE_INT);

  // Open connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch tasks
  $tasks = TaskRepository::getDoneTasks($conexion, $start, $length, $search, $sort_column_index, $sort_direction);
  $total_records = TaskRepository::getTotalDoneTasksCount($conexion);
  $total_filtered_records = TaskRepository::getTotalFilteredDoneTasksCount($conexion, $search);
} catch (Exception $e) {
  // Handle exceptions
  $response = array(
    "error" => "An error occurred while processing your request: " . $e->getMessage()
  );
  echo json_encode($response);
  exit;
} finally {
  // Ensure connection is closed
  Conexion::cerrar_conexion();
}

// Prepare and send response
$response = array(
  "draw" => $draw,
  "recordsTotal" => $total_records,
  "recordsFiltered" => $total_filtered_records,
  "data" => $tasks
);

echo json_encode($response);
