<?php
session_start();
if(isset($_POST['guardar_provider_subitem'])){
  Conexion::abrir_conexion();
  $provider_subitem = new ProviderSubitem('', $_POST['id_subitem'], $_POST['provider'], $_POST['price']);
  RepositorioProviderSubitem::insertar_provider_subitem(Conexion::obtener_conexion(), $provider_subitem);
  $subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $provider_subitem-> obtener_id_subitem());
  $item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem-> obtener_id_item());
  $id_rfq = $item-> obtener_id_rfq();
  $description_comment = 'A new provider was created for the subitem:
    <b>ELOGIC PROPOSAL</b>
    <b>Brand:</b>
    ' . $subitem-> obtener_brand() . '
    <b>Part number:</b>
    ' . $subitem-> obtener_part_number();
  $comment = new Comment('', $id_rfq, $_SESSION['id_usuario'], $description_comment, '');
  RepositorioComment::insertar_comment(Conexion::obtener_conexion(), $comment);
  Conexion::cerrar_conexion();
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#caja_items');
}
?>
