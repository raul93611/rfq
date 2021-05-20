<?php
session_start();
if(isset($_POST['guardar_provider_subitem'])){
  Database::open_connection();
  $provider_subitem = new ProviderSubitem('', $_POST['id_subitem'], $_POST['provider'], $_POST['price']);
  RepositorioProviderSubitem::insertar_provider_subitem(Database::get_connection(), $provider_subitem);
  $subitem = RepositorioSubitem::obtener_subitem_por_id(Database::get_connection(), $provider_subitem-> obtener_id_subitem());
  $item = RepositorioItem::obtener_item_por_id(Database::get_connection(), $subitem-> obtener_id_item());
  $id_rfq = $item-> obtener_id_rfq();
  AuditTrailRepository::create_audit_trail_subitem_created(Database::get_connection(), $_POST['id_subitem'], 'Provider', $_POST['provider'], 'Provider', $id_rfq);
  Database::close_connection();
  Redirection::redirect(EDIT_QUOTE . '/' . $id_rfq . '#caja_items');
}
?>
