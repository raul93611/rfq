<?php
session_start();
if (isset($_POST['guardar_cambios_provider'])) {
  Conexion::abrir_conexion();
  $provider_editado = RepositorioProvider::actualizar_provider(Conexion::obtener_conexion(), $_POST['id_provider'], $_POST['provider'], $_POST['price']);
  $provider = RepositorioProvider::obtener_provider_por_id(Conexion::obtener_conexion(), $_POST['id_provider']);
  $item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $provider-> obtener_id_item());
  $cambios = [];
  if($_POST['provider'] != $_POST['provider_original']){
    $cambios[] = 'provider';
  }

  if($_POST['price'] != $_POST['price_original']){
    $cambios[] = 'price';
  }

  if(count($cambios)){
    $cambios = implode(',', $cambios);
    $description_comment = 'A provider was modified. The fields: <b>' . $cambios . '</b>
    <b>Project specifications</b>
    <b>Brand:</b>
    ' . $item-> obtener_brand_project() . '
    <b>Part number:</b>
    ' . $item-> obtener_part_number_project();
    $comment = new Comment('', $_POST['id_rfq'], $_SESSION['id_usuario'], $description_comment, '');
    RepositorioComment::insertar_comment(Conexion::obtener_conexion(), $comment);
  }
  Conexion::cerrar_conexion();
  if($provider_editado){
    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq'] . '#caja_items');
  }
}
?>
