<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$tasks = TaskRepository::get_done(Conexion::obtener_conexion());
Conexion::cerrar_conexion();
$json = [];
foreach ($tasks as $key => $task) {
  $row = [
    '<a class="edit_task_button" data="' . $task-> get_id() . '" href="#">' . $task-> get_title() . '</a>',
    $task-> get_id_user_name(),
    $task-> get_assigned_user_name()
  ];
  array_push($json, $row);
}
echo json_encode(array(
  'data' => $json
));
?>
