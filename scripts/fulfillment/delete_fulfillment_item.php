<?php
header('Content-Type: application/json');
Database::open_connection();
$item = ItemRepository::get_by_id(Database::get_connection(), $_POST['id_item']);
FulfillmentItemRepository::delete(Database::get_connection(), $_POST['id_fulfillment_item']);
$total_cost = FulfillmentItemRepository::get_total_cost(Database::get_connection(), $_POST['id_item']);
ItemRepository::set_fulfillment_profit(Database::get_connection(), $item-> get_total_price()-$total_cost, $_POST['id_item']);
QuoteRepository::set_fulfillment_profit_and_total(Database::get_connection(), $item-> get_id_quote());
Database::close_connection();
echo json_encode(array(
  'id_quote' => $item-> get_id_quote()
));
?>
