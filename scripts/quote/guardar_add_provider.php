<?php
if(isset($_POST['guardar_provider'])){
  Conexion::abrir_conexion();
  $provider = new Provider('', $_POST['id_item'], $_POST['provider'], $_POST['price']);
  $provider_insertado = RepositorioProvider::insertar_provider(Conexion::obtener_conexion(), $provider);
  $item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $provider-> obtener_id_item());
  $id_rfq = $item-> obtener_id_rfq();
  AuditTrailRepository::create_audit_trail_item_created(Conexion::obtener_conexion(), $_POST['id_item'], 'Provider', $_POST['provider'], 'Provider', $id_rfq);
  Conexion::cerrar_conexion();
  if($provider_insertado){
    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#item' . $_POST['id_item']);
  }
}
?>
