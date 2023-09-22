<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
TypeOfProjectRepository::update(Conexion::obtener_conexion(), $_POST['name'], $_POST['id_type_of_project']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'reponse'=> 'success'
));
?>
