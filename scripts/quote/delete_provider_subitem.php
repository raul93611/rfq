<?php
session_start();
Database::open_connection();
$provider_subitem = ProviderSubitemRepository::get_by_id(Database::get_connection(), $id_provider_subitem);
$subitem = RepositorioSubitem::obtener_subitem_por_id(Database::get_connection(), $provider_subitem->get_id_subitem());
$item = ItemRepository::get_by_id(Database::get_connection(), $subitem-> get_id_item());
ProviderSubitemRepository::delete(Database::get_connection(), $id_provider_subitem);
AuditTrailRepository::create_audit_trail_subitem_provider_deleted(Database::get_connection(), $provider_subitem-> get_provider(), 'Provider', $subitem-> get_id(), $item-> get_id_quote());
Database::close_connection();
Redirection::redirect(EDIT_QUOTE . '/' . $item-> get_id_quote() . '#subitem' . $subitem-> get_id());
?>
