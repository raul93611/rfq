<?php
Database::open_connection();
ReQuoteRepository::reload_requote(Database::get_connection(), $id_rfq);
Database::close_connection();
Redireccion::redirigir(RE_QUOTE . $id_rfq);
?>
