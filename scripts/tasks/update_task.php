<?php
header('Content-Type: application/json');
$task_comment = TaskCommentRepository::validate($_POST);
Conexion::abrir_conexion();
$task = new Task('', '', $_POST['assigned_user'], null, null, null, $_POST['status']);
TaskRepository::update(Conexion::obtener_conexion(), $task, $_POST['id_task']);
$task = TaskRepository::get_one(Conexion::obtener_conexion(), $_POST['id_task']);
$user = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $task-> get_id_user());
if($task_comment){
  TaskCommentRepository::insert(Conexion::obtener_conexion(), $task_comment);
  Email::send_email_task_comment($task-> get_users_to_notify($_SESSION['user']-> obtener_id()), $task);
}
if($_POST['status'] == 'done' && ($_POST['old_status'] != $_POST['status'])){
  Email::send_email_task_done($user-> obtener_email(), $task);
}
Conexion::cerrar_conexion();
echo json_encode(array(
  'result' => true,
));
?>
