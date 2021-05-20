<?php
if(isset($_POST['save_re_quote_subitem_provider'])){
  $re_quote_subitem_provider = new ReQuoteSubitemProvider('', $_POST['id_re_quote_subitem'], $_POST['provider'], $_POST['price']);
  Database::open_connection();
  $re_quote_subitem = ReQuoteSubitemRepository::get_re_quote_subitem_by_id(Database::get_connection(), $_POST['id_re_quote_subitem']);
  $re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Database::get_connection(), $re_quote_subitem-> get_id_re_quote_item());
  $re_quote = ReQuoteRepository::get_re_quote_by_id(Database::get_connection(), $re_quote_item-> get_id_re_quote());
  ReQuoteSubitemProviderRepository::insert_re_quote_subitem_provider(Database::get_connection(), $re_quote_subitem_provider);
  Database::close_connection();
  Redirection::redirect(RE_QUOTE . $re_quote-> get_id_rfq());
}
?>
