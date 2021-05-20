<?php
echo "string";
if(isset($_POST['save_edit_re_quote_provider'])){
  Database::open_connection();
  ReQuoteProviderRepository::update_re_quote_provider(Database::get_connection(), $_POST['provider'], $_POST['price'], $_POST['id_re_quote_provider']);
  $re_quote_provider = ReQuoteProviderRepository::get_re_quote_provider_by_id(Database::get_connection(), $_POST['id_re_quote_provider']);
  $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Database::get_connection(), $re_quote_provider-> get_id_re_quote_item());
  $re_quote = ReQuoteRepository::get_re_quote_by_id(Database::get_connection(), $re_quote_item-> get_id_re_quote());
  ReQuoteAuditTrailRepository::edit_provider_item_events(Database::get_connection(), $_POST['provider'], $_POST['provider_original'], $_POST['price'], $_POST['price_original'], $re_quote_provider-> get_id_re_quote_item(), $re_quote_item-> get_id_re_quote());
  Database::close_connection();
  Redireccion::redirigir(RE_QUOTE . $re_quote-> get_id_rfq() . '#item' . $re_quote_provider-> get_id_re_quote_item());
}
?>
