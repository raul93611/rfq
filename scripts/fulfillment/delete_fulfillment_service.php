<?php
header('Content-Type: application/json');
Database::open_connection();
$service = ServiceRepository::get_service(Database::get_connection(), $_POST['id_service']);
FulfillmentServiceRepository::delete(Database::get_connection(), $_POST['id_fulfillment_service']);
$total_cost = FulfillmentServiceRepository::get_total_cost(Database::get_connection(), $_POST['id_service']);
ServiceRepository::set_fulfillment_profit(Database::get_connection(), $service-> get_total_price()-$total_cost, $_POST['id_service']);
RepositorioRfq::set_services_fulfillment_profit_and_total(Database::get_connection(), $service-> get_id_rfq());
Database::close_connection();
echo json_encode(array(
  'id_rfq' => $service-> get_id_rfq()
));
?>
