<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $_POST['id_item']);
FulfillmentItemRepository::delete(Conexion::obtener_conexion(), $_POST['id_fulfillment_item']);
$total_cost = FulfillmentItemRepository::get_total_cost(Conexion::obtener_conexion(), $_POST['id_item']);
RepositorioItem::set_fulfillment_profit(Conexion::obtener_conexion(), $item-> obtener_total_price()-$total_cost, $_POST['id_item']);
RepositorioRfq::set_fulfillment_profit_and_total(Conexion::obtener_conexion(), $item-> obtener_id_rfq());
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq' => $item-> obtener_id_rfq()
));
?>
