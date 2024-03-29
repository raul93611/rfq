<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$fulfillment_subitem = FulfillmentSubitemRepository::get_one(Conexion::obtener_conexion(), $_POST['id_fulfillment_subitem']);
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $_POST['id_subitem']);
FulfillmentSubitemRepository::delete(Conexion::obtener_conexion(), $_POST['id_fulfillment_subitem']);
$total_cost = FulfillmentSubitemRepository::get_total_cost(Conexion::obtener_conexion(), $_POST['id_subitem']);
RepositorioSubitem::set_fulfillment_profit(Conexion::obtener_conexion(), $subitem-> obtener_total_price()-$total_cost, $_POST['id_subitem']);
RepositorioRfq::set_fulfillment_profit_and_total(Conexion::obtener_conexion(), $_POST['id_rfq']);
FulfillmentAuditTrailRepository::create_audit_trail_item_deleted(Conexion::obtener_conexion(), $fulfillment_subitem-> get_provider(), 'Provider', $_POST['id_rfq']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq' => $_POST['id_rfq']
));
?>
