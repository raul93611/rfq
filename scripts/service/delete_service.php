<?php
Database::open_connection();
$service = ServiceRepository::get_service(Database::get_connection(), $id_service);
ServiceRepository::delete_service(Database::get_connection(), $id_service);
Database::close_connection();
Redirection::redirect(EDIT_QUOTE . '/' . $service-> get_id_quote() . '#services_table');
?>
