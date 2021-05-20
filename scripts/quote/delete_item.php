<?php
session_start();
Database::open_connection();
$item = RepositorioItem::obtener_item_por_id(Database::get_connection(), $id_item);
$id_rfq = $item-> obtener_id_rfq();
$subitems = RepositorioSubitem::obtener_subitems_por_id_item(Database::get_connection(), $id_item);
if(count($subitems)){
  foreach ($subitems as $subitem) {
    RepositorioSubitem::delete_subitem(Database::get_connection(), $subitem-> obtener_id());
  }
}
AuditTrailRepository::create_audit_trail_item_deleted(Database::get_connection(), 'Item', $item-> obtener_part_number_project(), 'Part Number', $id_rfq);
RepositorioItem::delete_item(Database::get_connection(), $id_item);
Database::close_connection();
Redirection::redirect(EDIT_QUOTE . '/' . $id_rfq . '#caja_items');
?>
