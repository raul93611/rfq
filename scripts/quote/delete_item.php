<?php
Conexion::abrir_conexion();
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $id_item);
$id_rfq = $item-> obtener_id_rfq();
$subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $id_item);
if(count($subitems)){
  foreach ($subitems as $subitem) {
    RepositorioSubitem::delete_subitem(Conexion::obtener_conexion(), $subitem-> obtener_id());
  }
}
AuditTrailRepository::create_audit_trail_item_deleted(Conexion::obtener_conexion(), 'Item', $item-> obtener_part_number_project(), 'Part Number', $id_rfq);
RepositorioItem::delete_item(Conexion::obtener_conexion(), $id_item);
Conexion::cerrar_conexion();
Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#caja_items');
?>
