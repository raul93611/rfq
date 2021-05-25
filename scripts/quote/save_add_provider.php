<?php
session_start();
if(isset($_POST['guardar_provider'])){
  Database::open_connection();
  $provider = new Provider('', $_POST['id_item'], $_POST['provider'], $_POST['price']);
  $inserted_provider = ProviderRepository::insert(Database::get_connection(), $provider);
  $item = ItemRepository::get_by_id(Database::get_connection(), $provider-> get_id_item());
  $id_quote = $item-> get_id_quote();
  AuditTrailRepository::create_audit_trail_item_created(Database::get_connection(), $_POST['id_item'], 'Provider', $_POST['provider'], 'Provider', $id_quote);
  Database::close_connection();
  if($inserted_provider){
    Redirection::redirect(EDIT_QUOTE . '/' . $id_quote . '#item' . $_POST['id_item']);
  }
}
?>
