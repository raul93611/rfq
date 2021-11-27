<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$fulfillment_service = FulfillmentServiceRepository::get_one(Conexion::obtener_conexion(), $_POST['id_fulfillment_service']);
$service = ServiceRepository::get_service(Conexion::obtener_conexion(), $_POST['id_service']);
FulfillmentServiceRepository::delete(Conexion::obtener_conexion(), $_POST['id_fulfillment_service']);
$total_cost = FulfillmentServiceRepository::get_total_cost(Conexion::obtener_conexion(), $_POST['id_service']);
ServiceRepository::set_fulfillment_profit(Conexion::obtener_conexion(), $service-> get_total_price()-$total_cost, $_POST['id_service']);
RepositorioRfq::set_services_fulfillment_profit_and_total(Conexion::obtener_conexion(), $service-> get_id_rfq());
FulfillmentAuditTrailRepository::create_audit_trail_item_deleted(Conexion::obtener_conexion(), $fulfillment_service-> get_provider(), 'Provider', $service-> get_id_rfq());
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq' => $service-> get_id_rfq()
));
?>
