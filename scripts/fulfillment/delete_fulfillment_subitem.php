<?php
header('Content-Type: application/json');
Database::open_connection();
$subitem = RepositorioSubitem::obtener_subitem_por_id(Database::get_connection(), $_POST['id_subitem']);
FulfillmentSubitemRepository::delete(Database::get_connection(), $_POST['id_fulfillment_subitem']);
$total_cost = FulfillmentSubitemRepository::get_total_cost(Database::get_connection(), $_POST['id_subitem']);
RepositorioSubitem::set_fulfillment_profit(Database::get_connection(), $subitem-> obtener_total_price()-$total_cost, $_POST['id_subitem']);
RepositorioRfq::set_fulfillment_profit_and_total(Database::get_connection(), $_POST['id_rfq']);
Database::close_connection();
echo json_encode(array(
  'id_rfq' => $_POST['id_rfq']
));
?>
