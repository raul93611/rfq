<?php
header('Content-Type: application/json');
Database::open_connection();
$item = RepositorioItem::obtener_item_por_id(Database::get_connection(), $_POST['id_item']);
$real_cost = ($_POST['quantity']*$_POST['unit_cost'])+$_POST['other_cost'];
FulfillmentItemRepository::update(Database::get_connection(), $_POST['id_fulfillment_item'], $_POST['provider'], $_POST['quantity'], $_POST['unit_cost'], $_POST['other_cost'], $real_cost);
$total_cost = FulfillmentItemRepository::get_total_cost(Database::get_connection(), $_POST['id_item']);
RepositorioItem::set_fulfillment_profit(Database::get_connection(), $item-> obtener_total_price()-$total_cost, $_POST['id_item']);
RepositorioRfq::set_fulfillment_profit_and_total(Database::get_connection(), $item-> obtener_id_rfq());
Database::close_connection();
echo json_encode(array(
  'id_rfq'=> $item-> obtener_id_rfq()
));
?>
