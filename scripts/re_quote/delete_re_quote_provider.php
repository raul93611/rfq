<?php
Database::open_connection();
$re_quote_provider = ReQuoteProviderRepository::get_re_quote_provider_by_id(Database::get_connection(), $id_re_quote_provider);
$re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Database::get_connection(), $re_quote_provider-> get_id_re_quote_item());
$re_quote = ReQuoteRepository::get_re_quote_by_id(Database::get_connection(), $re_quote_item-> get_id_re_quote());
ReQuoteProviderRepository::delete_re_quote_provider(Database::get_connection(), $id_re_quote_provider);
ReQuoteAuditTrailRepository::create_audit_trail_item_provider_deleted(Database::get_connection(), $re_quote_provider-> get_provider(), 'Provider', $re_quote_item-> get_id(), $re_quote_item-> get_id_re_quote());
Database::close_connection();
Redirection::redirect(RE_QUOTE . $re_quote-> get_id_quote() . '#item' . $re_quote_item-> get_id());
?>
