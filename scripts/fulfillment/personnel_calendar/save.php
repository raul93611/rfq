<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$event = new CalendarEvent('', $_POST['id_personnel'], $_POST['name'], $_POST["start"], $_POST["end"], $_POST["color"]);
CalendarEventRepository::save(Conexion::obtener_conexion(), $event);
Conexion::cerrar_conexion();
echo json_encode(array(
  'data'=> 'success'
));