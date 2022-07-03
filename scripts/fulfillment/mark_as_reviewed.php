<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$fulfillment_item = FulfillmentItemRepository::get_one(Conexion::obtener_conexion(), $_POST['id_fulfillment_item']);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $_POST['id_item']);
FulfillmentItemRepository::mark_as_reviewed(Conexion::obtener_conexion(), $_POST['id_fulfillment_item']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq' => $item-> obtener_id_rfq()
));
?>
