<?php
session_start();
if(isset($_POST['save_tracking_subitem'])){
  Database::open_connection();
  $subitem = RepositorioSubitem::obtener_subitem_por_id(Database::get_connection(), $_POST['id_subitem']);
  $item = ItemRepository::get_by_id(Database::get_connection(), $subitem-> get_id_item());
  $delivery_date = CommentRepository::english_format_to_mysql_date($_POST['delivery_date']);
  $tracking = new TrackingSubitem('', $_POST['id_subitem'], $_POST['quantity_shipped'], htmlspecialchars($_POST['tracking_number']), $delivery_date, $_POST['signed_by']);
  TrackingSubitemRepository::insert_tracking(Database::get_connection(), $tracking);
  Database::close_connection();
  Redirection::redirect(TRACKING . $item-> get_id_quote());
}
?>
