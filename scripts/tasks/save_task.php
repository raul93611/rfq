<?php
header('Content-Type: application/json');
try {
  // Validate session and POST data
  if (!isset($_SESSION['user'])) {
    throw new Exception("User is not logged in.");
  }

  $user_id = $_SESSION['user']->obtener_id();
  $assigned_user_id = filter_input(INPUT_POST, 'assigned_user', FILTER_VALIDATE_INT);
  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  if (!$assigned_user_id || !$title || !$message) {
    throw new Exception("Invalid input data.");
  }

  // Open connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Create and insert task
  $task = new Task('', $user_id, $assigned_user_id, null, $title, $message, 'todo');
  $id_task = TaskRepository::insert($conexion, $task);

  // Prepare response
  $response = array('result' => true);
} catch (Exception $e) {
  // Handle exceptions
  $response = array(
    'result' => false,
    'error' => "An error occurred: " . $e->getMessage()
  );
} finally {
  // Ensure connection is closed
  Conexion::cerrar_conexion();
}

// Send JSON response
echo json_encode($response);
