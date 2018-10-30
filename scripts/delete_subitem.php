<?php
session_start();
Conexion::abrir_conexion();
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $id_subitem);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem-> obtener_id_item());
$id_rfq = $item-> obtener_id_rfq();
RepositorioSubitem::delete_subitem(Conexion::obtener_conexion(), $id_subitem);
$description_comment = 'A subitem was deleted:
<b>ELOGIC PROPOSAL</b>
<b>Brand:</b>
' . $subitem-> obtener_brand() . '
<b>Part number:</b>
' . $subitem-> obtener_part_number() . '
';
$comment = new Comment('', $id_rfq, $_SESSION['id_usuario'], $description_comment, '');
RepositorioComment::insertar_comment(Conexion::obtener_conexion(), $comment);
Conexion::cerrar_conexion();
Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#caja_items');
?>
