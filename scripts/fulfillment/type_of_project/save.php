<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$typeOfProject = new TypeOfProject('', $_POST['name']);
TypeOfProjectRepository::save(Conexion::obtener_conexion(), $typeOfProject);
Conexion::cerrar_conexion();
echo json_encode(array(
  'data'=> 'success'
));
