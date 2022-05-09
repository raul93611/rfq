<?php
header('Content-Type: application/json');
for ($i = 0; $i <= $_POST['shipping_counter']; $i++) {
  $shippings[] = $_POST['fulfillment_shipping' . $i];
  $shippings_original[] = $_POST['fulfillment_shipping_original' . $i];
  $amounts[] = $_POST['amount' . $i];
  $amounts_original[] = $_POST['amount_original' . $i];
}
Conexion::abrir_conexion();
RepositorioRfq::update_fulfillment_shipping(Conexion::obtener_conexion(), implode('|', $shippings), implode('|', $amounts), $_POST['id_rfq']);
RepositorioRfq::set_fulfillment_profit_and_total(Conexion::obtener_conexion(), $_POST['id_rfq']);
FulfillmentAuditTrailRepository::shipping_event(Conexion::obtener_conexion(), $shippings, $shippings_original, $amounts, $amounts_original, $_POST['id_rfq']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq'=> $_POST['id_rfq'],
));
?>
