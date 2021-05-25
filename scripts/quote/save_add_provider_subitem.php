<?php
session_start();
if(isset($_POST['guardar_provider_subitem'])){
  Database::open_connection();
  $provider_subitem = new ProviderSubitem('', $_POST['id_subitem'], $_POST['provider'], $_POST['price']);
  ProviderSubitemRepository::insert(Database::get_connection(), $provider_subitem);
  $subitem = RepositorioSubitem::obtener_subitem_por_id(Database::get_connection(), $provider_subitem-> get_id_subitem());
  $item = ItemRepository::get_by_id(Database::get_connection(), $subitem-> get_id_item());
  $id_quote = $item-> get_id_quote();
  AuditTrailRepository::create_audit_trail_subitem_created(Database::get_connection(), $_POST['id_subitem'], 'Provider', $_POST['provider'], 'Provider', $id_quote);
  Database::close_connection();
  Redirection::redirect(EDIT_QUOTE . '/' . $id_quote . '#caja_items');
}
?>
