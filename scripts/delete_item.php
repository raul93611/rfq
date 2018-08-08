<?php
Conexion::abrir_conexion();
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $id_item);
$id_rfq = $item-> obtener_id_rfq();
RepositorioItem::delete_item(Conexion::obtener_conexion(), $id_item);
Conexion::cerrar_conexion();
Redireccion::redirigir1(EDITAR_COTIZACION . '/' . $id_rfq);
?>
