<?php
if (isset($_POST['guardar_cambios_provider'])) {
  Conexion::abrir_conexion();
  $provider_editado = RepositorioProvider::actualizar_provider(Conexion::obtener_conexion(), $_POST['id_provider'], $_POST['provider'], $_POST['price']);
  $provider = RepositorioProvider::obtener_provider_por_id(Conexion::obtener_conexion(), $_POST['id_provider']);
  $item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $provider-> obtener_id_item());
  AuditTrailRepository::edit_provider_item_events(Conexion::obtener_conexion(), $_POST['provider'], $_POST['provider_original'], $_POST['price'], $_POST['price_original'], $item-> obtener_id(), $_POST['id_rfq']);
  Conexion::cerrar_conexion();
  if($provider_editado){
    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq'] . '#item' . $item-> obtener_id());
  }
}
?>
