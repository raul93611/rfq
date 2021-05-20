<?php
Database::open_connection();
$re_quote_subitem = ReQuoteSubitemRepository::get_re_quote_subitem_by_id(Database::get_connection(), $id_re_quote_subitem);
$re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Database::get_connection(), $re_quote_subitem-> get_id_re_quote_item());
$re_quote = ReQuoteRepository::get_re_quote_by_id(Database::get_connection(), $re_quote_item-> get_id_re_quote());
ReQuoteSubitemRepository::delete_re_quote_subitem(Database::get_connection(), $id_re_quote_subitem);
Database::close_connection();
Redirection::redirect(RE_QUOTE . $re_quote-> get_id_rfq());
?>
