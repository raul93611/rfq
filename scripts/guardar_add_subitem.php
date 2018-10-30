<?php
session_start();
if(isset($_POST['guardar_subitem'])){
  Conexion::abrir_conexion();
  $subitem = new Subitem('', $_POST['id_item'], 0, $_POST['brand'], $_POST['brand_project'], $_POST['part_number'], $_POST['part_number_project'], htmlspecialchars($_POST['description']), htmlspecialchars($_POST['description_project']), $_POST['quantity'], 0, 0, $_POST['comments'], $_POST['website'], '');
  RepositorioSubitem::insertar_subitem(Conexion::obtener_conexion(), $subitem);
  $item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem-> obtener_id_item());
  $id_rfq = $item-> obtener_id_rfq();
  $description_comment = 'A new subitem was created for the item:
    <b>ELOGIC PROPOSAL</b>
    <b>Brand:</b>
    ' . $item-> obtener_brand() . '
    <b>Part number:</b>
    ' . $item-> obtener_part_number();
  $comment = new Comment('', $id_rfq, $_SESSION['id_usuario'], $description_comment, '');
  RepositorioComment::insertar_comment(Conexion::obtener_conexion(), $comment);
  Conexion::cerrar_conexion();
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#caja_items');
}
?>
