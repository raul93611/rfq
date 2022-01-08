<?php
header('Content-Type: application/json');
$delivery_date = RepositorioComment::english_format_to_mysql_date($_POST['delivery_date']);
$due_date = RepositorioComment::english_format_to_mysql_date($_POST['due_date']);
Conexion::abrir_conexion();
TrackingRepository::update_tracking(Conexion::obtener_conexion(), $_POST['quantity'], $_POST['carrier'], htmlspecialchars($_POST['tracking_number']), $delivery_date, $due_date, $_POST['signed_by'], htmlspecialchars($_POST['comments']), $_POST['id_tracking']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq'=> $_POST['id_rfq']
));
?>
