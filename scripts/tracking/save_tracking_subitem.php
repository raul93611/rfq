<?php
session_start();
if(isset($_POST['save_tracking_subitem'])){
  Database::open_connection();
  $subitem = RepositorioSubitem::obtener_subitem_por_id(Database::get_connection(), $_POST['id_subitem']);
  $item = RepositorioItem::obtener_item_por_id(Database::get_connection(), $subitem-> obtener_id_item());
  $delivery_date = RepositorioComment::english_format_to_mysql_date($_POST['delivery_date']);
  $tracking = new TrackingSubitem('', $_POST['id_subitem'], $_POST['quantity_shipped'], htmlspecialchars($_POST['tracking_number']), $delivery_date, $_POST['signed_by']);
  TrackingSubitemRepository::insert_tracking(Database::get_connection(), $tracking);
  Database::close_connection();
  Redireccion::redirigir(TRACKING . $item-> obtener_id_rfq());
}
?>
