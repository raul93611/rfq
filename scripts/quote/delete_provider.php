<?php
Conexion::abrir_conexion();
$provider = RepositorioProvider::obtener_provider_por_id(Conexion::obtener_conexion(), $id_provider);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $provider->obtener_id_item());
$deleted_provider = RepositorioProvider::delete_provider(Conexion::obtener_conexion(), $id_provider);
AuditTrailRepository::create_audit_trail_item_provider_deleted(Conexion::obtener_conexion(), $provider-> obtener_provider(), 'Provider', $item-> obtener_id(), $item-> obtener_id_rfq());
Conexion::cerrar_conexion();
if($deleted_provider){
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $item-> obtener_id_rfq() . '#item' . $item-> obtener_id());
}
?>
