<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
PersonnelRepository::update(Conexion::obtener_conexion(), $_POST['name'], $_POST["criteria"], $_POST['id_personnel']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'reponse'=> 'success'
));
?>
