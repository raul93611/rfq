<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
FulfillmentSubitemRepository::mark_as_reviewed(Conexion::obtener_conexion(), $_POST['id_fulfillment_subitem']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq' => $_POST['id_rfq']
));
?>