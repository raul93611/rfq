<?php
session_start();
Conexion::abrir_conexion();
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $id_item);
$id_rfq = $item-> obtener_id_rfq();
$subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $id_item);
if(count($subitems)){
  foreach ($subitems as $subitem) {
    RepositorioSubitem::delete_subitem(Conexion::obtener_conexion(), $subitem-> obtener_id());
  }
}
$description_comment = 'An item was deleted:
<b>Project Especifications</b>
<b>Brand:</b>
' . $item-> obtener_brand_project() . '
<b>Part number:</b>
' . $item-> obtener_part_number_project() . '
';
$comment = new Comment('', $id_rfq, $_SESSION['id_usuario'], $description_comment, '');
RepositorioComment::insertar_comment(Conexion::obtener_conexion(), $comment);
RepositorioItem::delete_item(Conexion::obtener_conexion(), $id_item);
Conexion::cerrar_conexion();
Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#caja_items');
?>
