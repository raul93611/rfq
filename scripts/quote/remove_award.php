<?php
Database::open_connection();
RepositorioRfq::remove_award(Database::get_connection(), $id_rfq);
Database::close_connection();
Redirection::redirect(EDIT_QUOTE . '/' . $id_rfq);
?>
