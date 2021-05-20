<?php
Database::open_connection();
RepositorioRfq::remove_award(Database::get_connection(), $id_rfq);
Database::close_connection();
Redireccion::redirigir(EDIT_QUOTE . '/' . $id_rfq);
?>
