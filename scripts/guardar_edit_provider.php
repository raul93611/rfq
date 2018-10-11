<?php
session_start();
if (isset($_POST['guardar_cambios_provider'])) {
  Conexion::abrir_conexion();
  $provider_editado = RepositorioProvider::actualizar_provider(Conexion::obtener_conexion(), $_POST['id_provider'], $_POST['provider'], $_POST['price']);
  Conexion::cerrar_conexion();
  if($provider_editado){
    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq'] . '#caja_items');
  }
}
?>
