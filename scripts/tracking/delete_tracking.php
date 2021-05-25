<?php
session_start();
Database::open_connection();
$tracking = TrackingRepository::get_tracking_by_id(Database::get_connection(), $id_tracking);
$item = ItemRepository::get_by_id(Database::get_connection(), $tracking-> get_id_item());
TrackingRepository::delete_tracking(Database::get_connection(), $id_tracking);
Database::close_connection();
Redirection::redirect(TRACKING . $item-> get_id_quote());
?>
