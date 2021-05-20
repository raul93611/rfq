<?php
session_start();
if (isset($_POST['guardar_cambios_provider_subitem'])) {
  Database::open_connection();
  RepositorioProviderSubitem::actualizar_provider_subitem(Database::get_connection(), $_POST['id_provider_subitem'], $_POST['provider'], $_POST['price']);
  $provider_subitem = RepositorioProviderSubitem::obtener_provider_subitem_por_id(Database::get_connection(), $_POST['id_provider_subitem']);
  $subitem = RepositorioSubitem::obtener_subitem_por_id(Database::get_connection(), $provider_subitem-> obtener_id_subitem());
  AuditTrailRepository::edit_provider_subitem_events(Database::get_connection(), $_POST['provider'], $_POST['provider_original'], $_POST['price'], $_POST['price_original'], $subitem-> obtener_id(), $_POST['id_rfq']);
  Database::close_connection();
  Redireccion::redirigir(EDIT_QUOTE . '/' . $_POST['id_rfq'] . '#subitem' . $subitem-> obtener_id());
}
?>
