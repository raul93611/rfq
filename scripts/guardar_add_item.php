<?php
session_start();
if (isset($_POST['guardar_item'])) {
  Conexion::abrir_conexion();
  $item = new Item('', $_POST['id_rfq'], $_SESSION['id_usuario'], 0, $_POST['brand'], $_POST['brand_project'], $_POST['part_number'], $_POST['part_number_project'], htmlspecialchars($_POST['description']), htmlspecialchars($_POST['description_project']), $_POST['quantity'], 0, 0, $_POST['comments'], $_POST['website'], '');
  $item_insertado = RepositorioItem::insertar_item(Conexion::obtener_conexion(), $item);
  $description_comment = 'A new item was created.
  <b>ELOGIC PROPOSAL</b>
  <b>Brand:</b><br>'
  . $_POST['brand'] . '<br>' .
  '<b>Part number:</b><br>'
   . $_POST['part_number'];
  $comment = new Comment('', $_POST['id_rfq'], $_SESSION['id_usuario'], $description_comment, '');
  RepositorioComment::insertar_comment(Conexion::obtener_conexion(), $comment);
  Conexion::cerrar_conexion();
  if($item_insertado){
    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq'] . '#caja_items');
  }
}
?>
