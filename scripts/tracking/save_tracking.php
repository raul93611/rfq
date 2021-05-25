<?php
session_start();
if(isset($_POST['save_tracking'])){
  Database::open_connection();
  $item = ItemRepository::get_by_id(Database::get_connection(), $_POST['id_item']);
  $delivery_date = CommentRepository::english_format_to_mysql_date($_POST['delivery_date']);
  $tracking = new Tracking('', $_POST['id_item'], $_POST['quantity_shipped'], htmlspecialchars($_POST['tracking_number']), $delivery_date, $_POST['signed_by']);
  TrackingRepository::insert_tracking(Database::get_connection(), $tracking);
  Database::close_connection();
  Redirection::redirect(TRACKING . $item-> get_id_quote());
}
?>
