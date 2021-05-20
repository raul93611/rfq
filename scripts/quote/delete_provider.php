<?php
session_start();
Database::open_connection();
$provider = RepositorioProvider::obtener_provider_por_id(Database::get_connection(), $id_provider);
$item = RepositorioItem::obtener_item_por_id(Database::get_connection(), $provider->obtener_id_item());
$deleted_provider = RepositorioProvider::delete_provider(Database::get_connection(), $id_provider);
AuditTrailRepository::create_audit_trail_item_provider_deleted(Database::get_connection(), $provider-> obtener_provider(), 'Provider', $item-> obtener_id(), $item-> obtener_id_rfq());
Database::close_connection();
if($deleted_provider){
  Redirection::redirect(EDIT_QUOTE . '/' . $item-> obtener_id_rfq() . '#item' . $item-> obtener_id());
}
?>
