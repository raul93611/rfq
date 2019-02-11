<?php
session_start();
if(isset($_POST['guardar_provider'])){
  Conexion::abrir_conexion();
  $provider = new Provider('', $_POST['id_item'], $_POST['provider'], $_POST['price']);
  $provider_insertado = RepositorioProvider::insertar_provider(Conexion::obtener_conexion(), $provider);
  $item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $provider-> obtener_id_item());
  $id_rfq = $item-> obtener_id_rfq();
  $description_comment = 'A new provider was created for the Item:
    <b>ELOGIC PROPOSAL</b>
    <b>Brand:</b>
    ' . $item-> obtener_brand() . '
    <b>Part number:</b>
    ' . $item-> obtener_part_number();
  $comment = new Comment('', $id_rfq, $_SESSION['id_usuario'], $description_comment, '');
  RepositorioComment::insertar_comment(Conexion::obtener_conexion(), $comment);
  Conexion::cerrar_conexion();
  if($provider_insertado){
    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#caja_items');
  }
}
?>
