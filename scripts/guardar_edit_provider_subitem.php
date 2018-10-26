<?php
session_start();
if (isset($_POST['guardar_cambios_provider_subitem'])) {
  Conexion::abrir_conexion();
  RepositorioProviderSubitem::actualizar_provider_subitem(Conexion::obtener_conexion(), $_POST['id_provider_subitem'], $_POST['provider'], $_POST['price']);
  $provider_subitem = RepositorioProviderSubitem::obtener_provider_subitem_por_id(Conexion::obtener_conexion(), $_POST['id_provider_subitem']);
  $subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $provider_subitem-> obtener_id_subitem());
  $cambios = [];
  if($_POST['provider'] != $_POST['provider_original']){
    $cambios[] = 'provider';
  }

  if($_POST['price'] != $_POST['price_original']){
    $cambios[] = 'price';
  }

  if(count($cambios)){
    $cambios = implode(',', $cambios);
    $description_comment = 'A subitem\'s provider was modified . The fields: <b>' . $cambios . '</b>
    <b>Project specifications</b>
    <b>Brand:</b>
    ' . $subitem-> obtener_brand_project() . '
    <b>Part number:</b>
    ' . $subitem-> obtener_part_number_project();
    $comment = new Comment('', $_POST['id_rfq'], $_SESSION['id_usuario'], $description_comment, '');
    RepositorioComment::insertar_comment(Conexion::obtener_conexion(), $comment);
  }
  Conexion::cerrar_conexion();
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq'] . '#caja_items');
}
?>
