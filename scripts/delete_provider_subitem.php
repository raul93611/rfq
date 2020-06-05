<?php
session_start();
Conexion::abrir_conexion();
$provider_subitem = RepositorioProviderSubitem::obtener_provider_subitem_por_id(Conexion::obtener_conexion(), $id_provider_subitem);
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $provider_subitem->obtener_id_subitem());
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem-> obtener_id_item());
RepositorioProviderSubitem::delete_provider_subitem(Conexion::obtener_conexion(), $id_provider_subitem);
AuditTrailRepository::create_audit_trail_subitem_provider_deleted(Conexion::obtener_conexion(), $provider_subitem-> obtener_provider(), 'Provider', $subitem-> obtener_id(), $item-> obtener_id_rfq());
Conexion::cerrar_conexion();
Redireccion::redirigir(EDITAR_COTIZACION . '/' . $item-> obtener_id_rfq() . '#subitem' . $subitem-> obtener_id());
?>
