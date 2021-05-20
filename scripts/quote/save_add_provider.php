<?php
session_start();
if(isset($_POST['guardar_provider'])){
  Database::open_connection();
  $provider = new Provider('', $_POST['id_item'], $_POST['provider'], $_POST['price']);
  $provider_insertado = RepositorioProvider::insertar_provider(Database::get_connection(), $provider);
  $item = RepositorioItem::obtener_item_por_id(Database::get_connection(), $provider-> obtener_id_item());
  $id_rfq = $item-> obtener_id_rfq();
  AuditTrailRepository::create_audit_trail_item_created(Database::get_connection(), $_POST['id_item'], 'Provider', $_POST['provider'], 'Provider', $id_rfq);
  Database::close_connection();
  if($provider_insertado){
    Redireccion::redirigir(EDIT_QUOTE . '/' . $id_rfq . '#item' . $_POST['id_item']);
  }
}
?>
