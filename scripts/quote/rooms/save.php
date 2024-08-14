<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$room = new Room('', $_POST['id_rfq'], $_POST["name"], $_POST["color"]);
RoomRepository::save(Conexion::obtener_conexion(), $room);
Conexion::cerrar_conexion();
echo json_encode(array(
  'data'=> 'success'
));
