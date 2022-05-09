<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
ProviderListRepository::delete(Conexion::obtener_conexion(), $_POST['id_provider']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'result' => true
));
?>
