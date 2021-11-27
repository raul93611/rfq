<?php
header('Content-Type: application/json');
$net30_cc = $_POST['net30_cc'] ? 1.0299 : 1;
Conexion::abrir_conexion();
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $_POST['id_subitem']);
$real_cost = (($_POST['quantity']*$_POST['unit_cost'])+$_POST['other_cost']) * $net30_cc;
$fulfillment_subitem = new FulfillmentSubitem('', $_POST['id_subitem'], $_POST['provider'], $_POST['quantity'], $_POST['unit_cost'], $_POST['other_cost'], $real_cost, $_POST['payment_term'], $_POST['net30_cc']);
$id = FulfillmentSubitemRepository::insert(Conexion::obtener_conexion(), $fulfillment_subitem);
$total_cost = FulfillmentSubitemRepository::get_total_cost(Conexion::obtener_conexion(), $_POST['id_subitem']);
RepositorioSubitem::set_fulfillment_profit(Conexion::obtener_conexion(), $subitem-> obtener_total_price()-$total_cost, $_POST['id_subitem']);
RepositorioRfq::set_fulfillment_profit_and_total(Conexion::obtener_conexion(), $_POST['id_rfq']);
FulfillmentAuditTrailRepository::create_audit_trail_subitem_created(Conexion::obtener_conexion(), $id, $_POST['provider'], 'Provider', $_POST['id_rfq']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq'=> $_POST['id_rfq']
));
?>
