<?php
session_start();
if (isset($_POST['guardar_cambios_provider'])) {
  Database::open_connection();
  $updated_provider = ProviderRepository::update(Database::get_connection(), $_POST['id_provider'], $_POST['provider'], $_POST['price']);
  $provider = ProviderRepository::get_by_id(Database::get_connection(), $_POST['id_provider']);
  $item = ItemRepository::get_by_id(Database::get_connection(), $provider-> get_id_item());
  AuditTrailRepository::edit_provider_item_events(Database::get_connection(), $_POST['provider'], $_POST['provider_original'], $_POST['price'], $_POST['price_original'], $item-> get_id(), $_POST['id_quote']);
  Database::close_connection();
  if($updated_provider){
    Redirection::redirect(EDIT_QUOTE . '/' . $_POST['id_quote'] . '#item' . $item-> get_id());
  }
}
?>
