<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$personnel = new Personnel('', $_POST['name'], $_POST["criteria"]);
PersonnelRepository::save(Conexion::obtener_conexion(), $personnel);
Conexion::cerrar_conexion();
echo json_encode(array(
  'data'=> 'success'
));
