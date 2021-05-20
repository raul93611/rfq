<?php
Database::open_connection();
$service = ServiceRepository::get_service(Database::get_connection(), $id_service);
ServiceRepository::delete_service(Database::get_connection(), $id_service);
Database::close_connection();
Redireccion::redirigir(EDIT_QUOTE . '/' . $service-> get_id_rfq() . '#services_table');
?>
