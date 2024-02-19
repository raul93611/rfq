<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$fulfillment_service = FulfillmentServiceRepository::get_one(Conexion::obtener_conexion(), $_POST['id_fulfillment_service']);
$service = ServiceRepository::get_service(Conexion::obtener_conexion(), $_POST['id_service']);
FulfillmentServiceRepository::mark_as_reviewed(Conexion::obtener_conexion(), $_POST['id_fulfillment_service']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq' => $service-> get_id_rfq()
));
?>
