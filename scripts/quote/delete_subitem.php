<?php
session_start();
Database::open_connection();
$subitem = RepositorioSubitem::obtener_subitem_por_id(Database::get_connection(), $id_subitem);
$item = RepositorioItem::obtener_item_por_id(Database::get_connection(), $subitem-> obtener_id_item());
$id_rfq = $item-> obtener_id_rfq();
RepositorioSubitem::delete_subitem(Database::get_connection(), $id_subitem);
AuditTrailRepository::create_audit_trail_item_deleted(Database::get_connection(), 'Subitem', $subitem-> obtener_part_number_project(), 'Part Number', $id_rfq);
Database::close_connection();
Redireccion::redirigir(EDIT_QUOTE . '/' . $id_rfq . '#caja_items');
?>
