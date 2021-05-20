<?php
session_start();
Database::open_connection();
$provider_subitem = RepositorioProviderSubitem::obtener_provider_subitem_por_id(Database::get_connection(), $id_provider_subitem);
$subitem = RepositorioSubitem::obtener_subitem_por_id(Database::get_connection(), $provider_subitem->obtener_id_subitem());
$item = RepositorioItem::obtener_item_por_id(Database::get_connection(), $subitem-> obtener_id_item());
RepositorioProviderSubitem::delete_provider_subitem(Database::get_connection(), $id_provider_subitem);
AuditTrailRepository::create_audit_trail_subitem_provider_deleted(Database::get_connection(), $provider_subitem-> obtener_provider(), 'Provider', $subitem-> obtener_id(), $item-> obtener_id_rfq());
Database::close_connection();
Redirection::redirect(EDIT_QUOTE . '/' . $item-> obtener_id_rfq() . '#subitem' . $subitem-> obtener_id());
?>
