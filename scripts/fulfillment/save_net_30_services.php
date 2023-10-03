<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
RepositorioRfq::save_net_30_services(Conexion::obtener_conexion(), $_POST['id_rfq'], $_POST['value']);
RepositorioRfq::set_services_fulfillment_profit_and_total(Conexion::obtener_conexion(), $_POST['id_rfq']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq'=> $_POST['id_rfq'],
));
?>
