<?php
header('Content-Type: application/json');
Database::open_connection();
$service = ServiceRepository::get_service(Database::get_connection(), $_POST['id_service']);
$real_cost = ($_POST['quantity']*$_POST['unit_cost'])+$_POST['other_cost'];
FulfillmentServiceRepository::update(Database::get_connection(), $_POST['id_fulfillment_service'], $_POST['provider'], $_POST['quantity'], $_POST['unit_cost'], $_POST['other_cost'], $real_cost);
$total_cost = FulfillmentServiceRepository::get_total_cost(Database::get_connection(), $_POST['id_service']);
ServiceRepository::set_fulfillment_profit(Database::get_connection(), $service-> get_total_price()-$total_cost, $_POST['id_service']);
QuoteRepository::set_services_fulfillment_profit_and_total(Database::get_connection(), $service-> get_id_quote());
Database::close_connection();
echo json_encode(array(
  'id_quote'=> $service-> get_id_quote()
));
?>
