<?php
Database::open_connection();
ReQuoteRepository::reload_requote(Database::get_connection(), $id_rfq);
Database::close_connection();
Redirection::redirect(RE_QUOTE . $id_rfq);
?>
