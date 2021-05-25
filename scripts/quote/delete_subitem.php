<?php
session_start();
Database::open_connection();
$subitem = RepositorioSubitem::obtener_subitem_por_id(Database::get_connection(), $id_subitem);
$item = ItemRepository::get_by_id(Database::get_connection(), $subitem-> get_id_item());
$id_quote = $item-> get_id_quote();
RepositorioSubitem::delete_subitem(Database::get_connection(), $id_subitem);
AuditTrailRepository::create_audit_trail_item_deleted(Database::get_connection(), 'Subitem', $subitem-> get_part_number_project(), 'Part Number', $id_quote);
Database::close_connection();
Redirection::redirect(EDIT_QUOTE . '/' . $id_quote . '#caja_items');
?>
