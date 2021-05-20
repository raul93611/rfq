<?php
if(isset($_POST['edit_service_button'])){
  Database::open_connection();
  ServiceRepository::edit_service(Database::get_connection(), $_POST['id_service'], htmlspecialchars($_POST['description']), $_POST['quantity'], $_POST['unit_price'], $_POST['quantity']*$_POST['unit_price']);
  Database::close_connection();
  Redireccion::redirigir(EDIT_QUOTE . '/' . $_POST['id_rfq'] . '#service' . $_POST['id_service']);
}
?>
