<?php
session_start();
Conexion::abrir_conexion();
$provider = RepositorioProvider::obtener_provider_por_id(Conexion::obtener_conexion(), $id_provider);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $provider->obtener_id_item());
$deleted_provider = RepositorioProvider::delete_provider(Conexion::obtener_conexion(), $id_provider);
$description_comment = 'A provider was deleted from the item:
<b>ELOGIC PROPOSAL</b>
<b>Brand:</b>
' . $item-> obtener_brand() . '
<b>Part number:</b>
' . $item-> obtener_part_number() . '
';
$comment = new Comment('', $item-> obtener_id_rfq(), $_SESSION['id_usuario'], $description_comment, '');
RepositorioComment::insertar_comment(Conexion::obtener_conexion(), $comment);
Conexion::cerrar_conexion();
if($deleted_provider){
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $item-> obtener_id_rfq() . '#caja_items');
}
?>
