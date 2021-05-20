<?php
session_start();
if (isset($_POST['guardar_cambios_provider'])) {
  Database::open_connection();
  $provider_editado = RepositorioProvider::actualizar_provider(Database::get_connection(), $_POST['id_provider'], $_POST['provider'], $_POST['price']);
  $provider = RepositorioProvider::obtener_provider_por_id(Database::get_connection(), $_POST['id_provider']);
  $item = RepositorioItem::obtener_item_por_id(Database::get_connection(), $provider-> obtener_id_item());
  AuditTrailRepository::edit_provider_item_events(Database::get_connection(), $_POST['provider'], $_POST['provider_original'], $_POST['price'], $_POST['price_original'], $item-> obtener_id(), $_POST['id_rfq']);
  Database::close_connection();
  if($provider_editado){
    Redireccion::redirigir(EDIT_QUOTE . '/' . $_POST['id_rfq'] . '#item' . $item-> obtener_id());
  }
}
?>
