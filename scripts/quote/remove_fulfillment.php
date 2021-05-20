<?php
Database::open_connection();
RepositorioRfq::remove_fulfillment(Database::get_connection(), $id_rfq);
Database::close_connection();
Redirection::redirect(EDIT_QUOTE . '/' . $id_rfq);
?>
