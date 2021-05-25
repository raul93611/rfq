<?php
if(isset($_POST['save_re_quote_provider'])){
  $re_quote_provider = new ReQuoteProvider('', $_POST['id_re_quote_item'], $_POST['provider'], $_POST['price']);
  Database::open_connection();
  ReQuoteProviderRepository::insert_re_quote_provider(Database::get_connection(), $re_quote_provider);
  $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Database::get_connection(), $_POST['id_re_quote_item']);
  $re_quote = ReQuoteRepository::get_re_quote_by_id(Database::get_connection(), $re_quote_item-> get_id_re_quote());
  Database::close_connection();
  Redirection::redirect(RE_QUOTE . $re_quote-> get_id_quote());
}
?>
