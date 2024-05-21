<?php
Conexion::abrir_conexion();
$tracking_subitem = TrackingSubitemRepository::get_tracking_subitem_by_id(Conexion::obtener_conexion(), $id_tracking_subitem);
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $tracking_subitem->get_id_subitem());
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem->obtener_id_item());
TrackingSubitemRepository::delete_tracking_subitem(Conexion::obtener_conexion(), $id_tracking_subitem);
Conexion::cerrar_conexion();
Redireccion::redirigir(TRACKING . $item->obtener_id_rfq());
