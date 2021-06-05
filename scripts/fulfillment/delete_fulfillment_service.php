<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$service = ServiceRepository::get_service(Conexion::obtener_conexion(), $_POST['id_service']);
FulfillmentServiceRepository::delete(Conexion::obtener_conexion(), $_POST['id_fulfillment_service']);
$total_cost = FulfillmentServiceRepository::get_total_cost(Conexion::obtener_conexion(), $_POST['id_service']);
ServiceRepository::set_fulfillment_profit(Conexion::obtener_conexion(), $service-> get_total_price()-$total_cost, $_POST['id_service']);
RepositorioRfq::set_services_fulfillment_profit_and_total(Conexion::obtener_conexion(), $service-> get_id_rfq());
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq' => $service-> get_id_rfq()
));
?>