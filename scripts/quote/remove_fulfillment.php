<?php
Database::open_connection();
QuoteRepository::remove_fulfillment(Database::get_connection(), $id_quote);
Database::close_connection();
Redirection::redirect(EDIT_QUOTE . '/' . $id_quote);
?>
