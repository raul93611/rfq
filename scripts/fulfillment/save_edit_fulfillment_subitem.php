<?php
header('Content-Type: application/json');
$net30_cc = $_POST['net30_cc'] ? 1.0299 : 1;
$readable_net30 = $_POST['net30_cc'] ? 'Enabled' : 'Disabled';
$readable_net30_original = $_POST['net30_cc_original'] ? 'Enabled' : 'Disabled';
Conexion::abrir_conexion();
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $_POST['id_subitem']);
$real_cost = (($_POST['quantity']*$_POST['unit_cost'])+$_POST['other_cost']) * $net30_cc;
FulfillmentSubitemRepository::update(Conexion::obtener_conexion(), $_POST['id_fulfillment_subitem'], $_POST['provider'], $_POST['quantity'], $_POST['unit_cost'], $_POST['other_cost'], $real_cost, $_POST['payment_term'], $_POST['net30_cc']);
$total_cost = FulfillmentSubitemRepository::get_total_cost(Conexion::obtener_conexion(), $_POST['id_subitem']);
RepositorioSubitem::set_fulfillment_profit(Conexion::obtener_conexion(), $subitem-> obtener_total_price()-$total_cost, $_POST['id_subitem']);
RepositorioRfq::set_fulfillment_profit_and_total(Conexion::obtener_conexion(), $_POST['id_rfq']);
FulfillmentAuditTrailRepository::edit_subitem_events(Conexion::obtener_conexion(), $_POST['provider'], $_POST['provider_original'], $_POST['quantity'], $_POST['quantity_original'], $_POST['unit_cost'], $_POST['unit_cost_original'], $_POST['other_cost'], $_POST['other_cost_original'], $_POST['payment_term'], $_POST['payment_term_original'], $readable_net30, $readable_net30_original, $_POST['id_fulfillment_subitem'], $_POST['id_rfq']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq' => $_POST['id_rfq']
));
?>
