<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$service = ServiceRepository::get_service(Conexion::obtener_conexion(), $_POST['id_service']);
$real_cost = ($_POST['quantity'] * $_POST['unit_cost']) + $_POST['other_cost'];
$fulfillment_service = new FulfillmentService(
  '', 
  $_POST['id_service'], 
  $_POST['provider'], 
  $_POST['quantity'], 
  $_POST['unit_cost'], 
  $_POST['other_cost'], 
  $real_cost, 
  $_POST['payment_term'], 
  0, 
  '', 
  htmlspecialchars($_POST["comment"]),
  empty($_POST["invoice"]) ? null : $_POST["invoice"],
  $_POST["transaction_date"]
);
$id = FulfillmentServiceRepository::insert(Conexion::obtener_conexion(), $fulfillment_service);
$total_cost = FulfillmentServiceRepository::get_total_cost(Conexion::obtener_conexion(), $_POST['id_service']);
ServiceRepository::set_fulfillment_profit(Conexion::obtener_conexion(), $service->get_total_price() - $total_cost, $_POST['id_service']);
RepositorioRfq::set_services_fulfillment_profit_and_total(Conexion::obtener_conexion(), $service->get_id_rfq());
FulfillmentAuditTrailRepository::create_audit_trail_service_created(Conexion::obtener_conexion(), $id, $_POST['provider'], 'Provider', $service->get_id_rfq());
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq' => $service->get_id_rfq()
));
