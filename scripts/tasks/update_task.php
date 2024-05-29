<?php
header('Content-Type: application/json');

try {
  // Validate input data
  $id_task = filter_input(INPUT_POST, 'id_task', FILTER_VALIDATE_INT);
  $assigned_user_id = filter_input(INPUT_POST, 'assigned_user', FILTER_VALIDATE_INT);
  $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);

  if (!$id_task || !$assigned_user_id || !$status) {
    throw new Exception("Invalid input data.");
  }

  $task_comment = TaskCommentRepository::validate($_POST);

  // Open connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Update task status
  $task = new Task('', '', $assigned_user_id, null, null, null, $status);
  TaskRepository::update($conexion, $task, $id_task);

  // Retrieve updated task and user details
  $task = TaskRepository::get_one($conexion, $id_task);
  $user = RepositorioUsuario::obtener_usuario_por_id($conexion, $task->get_id_user());

  // Insert task comment if it exists
  if ($task_comment) {
    TaskCommentRepository::insert($conexion, $task_comment);
  }

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
