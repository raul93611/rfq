<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
RepositorioRfq::update_fulfillment_shipping(Conexion::obtener_conexion(), $_POST['fulfillment_shipping'], $_POST['amount'], $_POST['id_rfq']);
RepositorioRfq::set_fulfillment_profit_and_total(Conexion::obtener_conexion(), $_POST['id_rfq']);
FulfillmentAuditTrailRepository::shipping_event(Conexion::obtener_conexion(), $_POST['fulfillment_shipping'], $_POST['fulfillment_shipping_original'], $_POST['amount'], $_POST['amount_original'], $_POST['id_rfq']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq'=> $_POST['id_rfq'],
));
?>
