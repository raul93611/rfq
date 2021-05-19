<?php
header('Content-Type: application/json');
$delivery_date = RepositorioComment::english_format_to_mysql_date($_POST['delivery_date']);
Conexion::abrir_conexion();
TrackingSubitemRepository::update_tracking_subitem(Conexion::obtener_conexion(), $_POST['quantity'], htmlspecialchars($_POST['tracking_number']), $delivery_date, $_POST['signed_by'], $_POST['id_tracking_subitem']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq'=> $_POST['id_rfq']
));
?>
