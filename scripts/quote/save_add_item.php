<?php
session_start();
if (isset($_POST['guardar_item'])) {
  Database::open_connection();
  $item = new Item('', $_POST['id_rfq'], $_SESSION['id_user'], 0, $_POST['brand'], $_POST['brand_project'], $_POST['part_number'], $_POST['part_number_project'], htmlspecialchars($_POST['description']), htmlspecialchars($_POST['description_project']), $_POST['quantity'], 0, 0, $_POST['comments'], $_POST['website'], '', null);
  $id = RepositorioItem::insertar_item(Database::get_connection(), $item);
  AuditTrailRepository::create_audit_trail_item_created(Database::get_connection(), $id, 'Item', $_POST['part_number_project'], 'Part Number',  $_POST['id_rfq']);
  Database::close_connection();
  if($id){
    Redireccion::redirigir(EDIT_QUOTE . '/' . $_POST['id_rfq'] . '#item' . $id);
  }
}
?>
