<?php
session_start();
Database::open_connection();
$item = ItemRepository::get_by_id(Database::get_connection(), $id_item);
$id_quote = $item-> get_id_quote();
$subitems = RepositorioSubitem::obtener_subitems_por_id_item(Database::get_connection(), $id_item);
if(count($subitems)){
  foreach ($subitems as $subitem) {
    RepositorioSubitem::delete_subitem(Database::get_connection(), $subitem-> get_id());
  }
}
AuditTrailRepository::create_audit_trail_item_deleted(Database::get_connection(), 'Item', $item-> get_part_number_project(), 'Part Number', $id_quote);
ItemRepository::delete_item(Database::get_connection(), $id_item);
Database::close_connection();
Redirection::redirect(EDIT_QUOTE . '/' . $id_quote . '#caja_items');
?>
