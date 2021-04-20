<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $_POST['id_subitem']);
$real_cost = ($_POST['quantity']*$_POST['unit_cost'])+$_POST['other_cost'];
FulfillmentSubitemRepository::update(Conexion::obtener_conexion(), $_POST['id_fulfillment_subitem'], $_POST['provider'], $_POST['quantity'], $_POST['unit_cost'], $_POST['other_cost'], $real_cost);
$total_cost = FulfillmentSubitemRepository::get_total_cost(Conexion::obtener_conexion(), $_POST['id_subitem']);
RepositorioSubitem::set_fulfillment_profit(Conexion::obtener_conexion(), $subitem-> obtener_total_price()-$total_cost, $_POST['id_subitem']);
RepositorioRfq::set_fulfillment_profit(Conexion::obtener_conexion(), $_POST['id_rfq']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq' => $_POST['id_rfq']
));
?>
