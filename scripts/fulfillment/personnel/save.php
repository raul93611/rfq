<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$personnel = new Personnel('', $_POST['name'], $_POST["criteria"], $_POST["id_type_of_project"]);
PersonnelRepository::save(Conexion::obtener_conexion(), $personnel);
Conexion::cerrar_conexion();
echo json_encode(array(
  'data'=> 'success'
));
