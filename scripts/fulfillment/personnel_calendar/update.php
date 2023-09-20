<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
CalendarEventRepository::update(Conexion::obtener_conexion(), $_POST['name'], $_POST["start"], $_POST["end"], $_POST["color"], $_POST['id_event']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'reponse'=> 'success'
));
?>
