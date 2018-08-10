<?php
Conexion::abrir_conexion();
$provider_subitem = RepositorioProviderSubitem::obtener_provider_subitem_por_id(Conexion::obtener_conexion(), $id_provider_subitem);
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $provider_subitem->obtener_id_subitem());
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem-> obtener_id_item());
RepositorioProviderSubitem::delete_provider_subitem(Conexion::obtener_conexion(), $id_provider_subitem);
Conexion::cerrar_conexion();
Redireccion::redirigir1(EDITAR_COTIZACION . '/' . $item-> obtener_id_rfq());
?>
