<?php
if(isset($_POST['save_re_quote'])){
  Database::open_connection();
  $re_quote = ReQuoteRepository::get_re_quote_by_id(Database::get_connection(), $_POST['id_re_quote']);
  $quote = QuoteRepository::get_by_id(Database::get_connection(), $re_quote-> get_id_quote());
  ReQuoteRepository::update_re_quote(Database::get_connection(), $_POST['payment_terms'], $_POST['total_cost'], $_POST['shipping'], $_POST['shipping_cost'], $_POST['id_re_quote']);
  ReQuoteAuditTrailRepository::items_table_events(Database::get_connection(), $_POST['payment_terms'], $_POST['payment_terms_original'], $_POST['shipping'], $_POST['shipping_original'], $_POST['shipping_cost'], $_POST['shipping_cost_original'], $_POST['id_re_quote']);
  Database::close_connection();
  Redirection::redirect(RE_QUOTE . $re_quote-> get_id_quote());
}
?>
