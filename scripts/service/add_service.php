<?php
if(isset($_POST['add_service_button'])){
  $service = new Service('', $_POST['id_rfq'], htmlspecialchars($_POST['description']), $_POST['quantity'], $_POST['unit_price'], $_POST['quantity']*$_POST['unit_price'], null);
  Database::open_connection();
  $id = ServiceRepository::store_service(Database::get_connection(), $service);
  Database::close_connection();
  Redirection::redirect(EDIT_QUOTE . '/' . $_POST['id_rfq'] . '#service' . $id);
}
?>
