<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$task = new Task('', $_SESSION['user']-> obtener_id(), $_POST['assigned_user'], null, $_POST['title'], $_POST['message'], 'todo');
$id_task = TaskRepository::insert(Conexion::obtener_conexion(), $task);
$task = TaskRepository::get_one(Conexion::obtener_conexion(), $id_task);
$assigned_user = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $_POST['assigned_user']);
Email::send_email_task_created($assigned_user-> obtener_email(), $task);
Conexion::cerrar_conexion();
echo json_encode(array(
  'result' => true,
));
?>
