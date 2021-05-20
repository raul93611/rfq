<?php
if(isset($_POST['save_re_quote_subitem'])){
  $re_quote_subitem = new ReQuoteSubitem('', $_POST['id_re_quote_item'], $_POST['brand'], $_POST['brand_project'], $_POST['part_number'], $_POST['part_number_project'], $_POST['description'], $_POST['description_project'], $_POST['quantity'], 0, 0, htmlspecialchars($_POST['comments']), $_POST['website'], 0);
  Database::open_connection();
  $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Database::get_connection(), $_POST['id_re_quote_item']);
  $re_quote = ReQuoteRepository::get_re_quote_by_id(Database::get_connection(), $re_quote_item-> get_id_re_quote());
  $id = ReQuoteSubitemRepository::insert_re_quote_subitem(Database::get_connection(), $re_quote_subitem);
  ReQuoteAuditTrailRepository::create_audit_trail_subitem_created(Database::get_connection(), $id, 'Subitem', $_POST['part_number_project'], 'Part Number', $re_quote_item-> get_id_re_quote());
  Database::close_connection();
  Redireccion::redirigir(RE_QUOTE . $re_quote-> get_id_rfq() . '#subitem' . $id);
}
?>
