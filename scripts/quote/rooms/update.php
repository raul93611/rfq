<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
RoomRepository::update(Conexion::obtener_conexion(), $_POST['name'], $_POST["color"], $_POST["id_room"]);
Conexion::cerrar_conexion();
echo json_encode(array(
  'reponse' => 'success'
));
