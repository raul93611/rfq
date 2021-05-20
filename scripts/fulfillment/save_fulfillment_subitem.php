<?php
header('Content-Type: application/json');
Database::open_connection();
$subitem = RepositorioSubitem::obtener_subitem_por_id(Database::get_connection(), $_POST['id_subitem']);
$real_cost = ($_POST['quantity']*$_POST['unit_cost'])+$_POST['other_cost'];
$fulfillment_subitem = new FulfillmentSubitem('', $_POST['id_subitem'], $_POST['provider'], $_POST['quantity'], $_POST['unit_cost'], $_POST['other_cost'], $real_cost);
FulfillmentSubitemRepository::insert(Database::get_connection(), $fulfillment_subitem);
$total_cost = FulfillmentSubitemRepository::get_total_cost(Database::get_connection(), $_POST['id_subitem']);
RepositorioSubitem::set_fulfillment_profit(Database::get_connection(), $subitem-> obtener_total_price()-$total_cost, $_POST['id_subitem']);
RepositorioRfq::set_fulfillment_profit_and_total(Database::get_connection(), $_POST['id_rfq']);
Database::close_connection();
echo json_encode(array(
  'id_rfq'=> $_POST['id_rfq']
));
?>
