<?php
Conexion::abrir_conexion();
$tracking = TrackingRepository::get_tracking_by_id(Conexion::obtener_conexion(), $id_tracking);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $tracking-> get_id_item());
TrackingRepository::delete_tracking(Conexion::obtener_conexion(), $id_tracking);
Conexion::cerrar_conexion();
Redireccion::redirigir(TRACKING . $item-> obtener_id_rfq());
?>
