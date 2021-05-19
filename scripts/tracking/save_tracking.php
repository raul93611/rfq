<?php
session_start();
if(isset($_POST['save_tracking'])){
  Conexion::abrir_conexion();
  $item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $_POST['id_item']);
  $delivery_date = RepositorioComment::english_format_to_mysql_date($_POST['delivery_date']);
  $tracking = new Tracking('', $_POST['id_item'], $_POST['quantity_shipped'], htmlspecialchars($_POST['tracking_number']), $delivery_date, $_POST['signed_by']);
  TrackingRepository::insert_tracking(Conexion::obtener_conexion(), $tracking);
  Conexion::cerrar_conexion();
  Redireccion::redirigir(TRACKING . $item-> obtener_id_rfq());
}
?>
