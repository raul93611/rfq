<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$service = ServiceRepository::get_service(Conexion::obtener_conexion(), $_POST['id_service']);
$real_cost = ($_POST['quantity']*$_POST['unit_cost'])+$_POST['other_cost'];
FulfillmentServiceRepository::update(Conexion::obtener_conexion(), $_POST['id_fulfillment_service'], $_POST['provider'], $_POST['quantity'], $_POST['unit_cost'], $_POST['other_cost'], $real_cost, $_POST['payment_term'], $_POST["comment"]);
$total_cost = FulfillmentServiceRepository::get_total_cost(Conexion::obtener_conexion(), $_POST['id_service']);
ServiceRepository::set_fulfillment_profit(Conexion::obtener_conexion(), $service-> get_total_price()-$total_cost, $_POST['id_service']);
RepositorioRfq::set_services_fulfillment_profit_and_total(Conexion::obtener_conexion(), $service-> get_id_rfq());
FulfillmentAuditTrailRepository::edit_service_events(Conexion::obtener_conexion(), $_POST['provider'], $_POST['provider_original'], $_POST['quantity'], $_POST['quantity_original'], $_POST['unit_cost'], $_POST['unit_cost_original'], $_POST['other_cost'], $_POST['other_cost_original'], $_POST['payment_term'], $_POST['payment_term_original'], $_POST['id_fulfillment_service'], $service-> get_id_rfq());
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq'=> $service-> get_id_rfq()
));
?>
