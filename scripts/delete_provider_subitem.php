<?php
session_start();
Conexion::abrir_conexion();
$provider_subitem = RepositorioProviderSubitem::obtener_provider_subitem_por_id(Conexion::obtener_conexion(), $id_provider_subitem);
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $provider_subitem->obtener_id_subitem());
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem-> obtener_id_item());
RepositorioProviderSubitem::delete_provider_subitem(Conexion::obtener_conexion(), $id_provider_subitem);
$description_comment = 'A subitem\'s provider was deleted:
<b>ELOGIC PROPOSAL</b>
<b>Brand:</b>
' . $subitem-> obtener_brand() . '
<b>Part number:</b>
' . $subitem-> obtener_part_number() . '
';
$comment = new Comment('', $item-> obtener_id_rfq(), $_SESSION['id_usuario'], $description_comment, '');
RepositorioComment::insertar_comment(Conexion::obtener_conexion(), $comment);
Conexion::cerrar_conexion();
Redireccion::redirigir(EDITAR_COTIZACION . '/' . $item-> obtener_id_rfq() . '#caja_items');
?>
