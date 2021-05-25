<?php
session_start();
Database::open_connection();
$provider = ProviderRepository::get_by_id(Database::get_connection(), $id_provider);
$item = ItemRepository::get_by_id(Database::get_connection(), $provider->get_id_item());
$deleted_provider = ProviderRepository::delete_provider(Database::get_connection(), $id_provider);
AuditTrailRepository::create_audit_trail_item_provider_deleted(Database::get_connection(), $provider-> get_provider(), 'Provider', $item-> get_id(), $item-> get_id_quote());
Database::close_connection();
if($deleted_provider){
  Redirection::redirect(EDIT_QUOTE . '/' . $item-> get_id_quote() . '#item' . $item-> get_id());
}
?>
