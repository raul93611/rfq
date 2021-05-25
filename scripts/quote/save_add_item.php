<?php
session_start();
if (isset($_POST['guardar_item'])) {
  Database::open_connection();
  $item = new Item('', $_POST['id_quote'], $_SESSION['id_user'], 0, $_POST['brand'], $_POST['brand_project'], $_POST['part_number'], $_POST['part_number_project'], $_POST['description'], $_POST['description_project'], $_POST['quantity'], 0, 0, $_POST['comments'], $_POST['website'], '', null);
  $id = ItemRepository::insert(Database::get_connection(), $item);
  AuditTrailRepository::create_audit_trail_item_created(Database::get_connection(), $id, 'Item', $_POST['part_number_project'], 'Part Number',  $_POST['id_quote']);
  Database::close_connection();
  if($id){
    Redirection::redirect(EDIT_QUOTE . '/' . $_POST['id_quote'] . '#item' . $id);
  }
}
?>
