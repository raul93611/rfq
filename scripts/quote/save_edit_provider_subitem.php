<?php
session_start();
if (isset($_POST['guardar_cambios_provider_subitem'])) {
  Database::open_connection();
  ProviderSubitemRepository::update(Database::get_connection(), $_POST['id_provider_subitem'], $_POST['provider'], $_POST['price']);
  $provider_subitem = ProviderSubitemRepository::get_by_id(Database::get_connection(), $_POST['id_provider_subitem']);
  $subitem = RepositorioSubitem::obtener_subitem_por_id(Database::get_connection(), $provider_subitem-> get_id_subitem());
  AuditTrailRepository::edit_provider_subitem_events(Database::get_connection(), $_POST['provider'], $_POST['provider_original'], $_POST['price'], $_POST['price_original'], $subitem-> get_id(), $_POST['id_quote']);
  Database::close_connection();
  Redirection::redirect(EDIT_QUOTE . '/' . $_POST['id_quote'] . '#subitem' . $subitem-> get_id());
}
?>
