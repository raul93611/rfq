<?php
session_start();
Database::open_connection();
$tracking_subitem = TrackingSubitemRepository::get_tracking_subitem_by_id(Database::get_connection(), $id_tracking_subitem);
$subitem = RepositorioSubitem::obtener_subitem_por_id(Database::get_connection(), $tracking_subitem-> get_id_subitem());
$item = ItemRepository::get_by_id(Database::get_connection(), $subitem-> get_id_item());
TrackingSubitemRepository::delete_tracking_subitem(Database::get_connection(), $id_tracking_subitem);
Redirection::redirect(TRACKING . $item-> get_id_quote());
Database::close_connection();
?>
