<?php
Conexion::abrir_conexion();
$provider = RepositorioProvider::obtener_provider_por_id(Conexion::obtener_conexion(), $id_provider);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $provider->obtener_id_item());
$deleted_provider = RepositorioProvider::delete_provider(Conexion::obtener_conexion(), $id_provider);
Conexion::cerrar_conexion();
if($deleted_provider){
  Redireccion::redirigir1(EDITAR_COTIZACION . '/' . $item-> obtener_id_rfq());
}
?>
