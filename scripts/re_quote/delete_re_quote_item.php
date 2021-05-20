<?php
Database::open_connection();
$re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Database::get_connection(), $id_re_quote_item);
$re_quote = ReQuoteRepository::get_re_quote_by_id(Database::get_connection(), $re_quote_item-> get_id_re_quote());
$re_quote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item(Database::get_connection(), $id_re_quote_item);
if(count($re_quote_subitems)){
  foreach ($re_quote_subitems as $key => $re_quote_subitem) {
    ReQuoteSubitemRepository::delete_re_quote_subitem(Database::get_connection(), $re_quote_subitem-> get_id());
  }
}
ReQuoteItemRepository::delete_re_quote_item(Database::get_connection(), $id_re_quote_item);
ReQuoteAuditTrailRepository::create_audit_trail_item_deleted(Database::get_connection(), 'Item', $re_quote_item-> get_part_number_project(), 'Part Number', $re_quote_item-> get_id_re_quote());
Database::close_connection();
Redireccion::redirigir(RE_QUOTE . $re_quote-> get_id_rfq());
?>
