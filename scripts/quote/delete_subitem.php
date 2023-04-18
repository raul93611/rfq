<?php
Conexion::abrir_conexion();
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $id_subitem);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem-> obtener_id_item());
$id_rfq = $item-> obtener_id_rfq();
RepositorioSubitem::delete_subitem(Conexion::obtener_conexion(), $id_subitem);
AuditTrailRepository::create_audit_trail_item_deleted(Conexion::obtener_conexion(), 'Subitem', $subitem-> obtener_part_number_project(), 'Part Number', $id_rfq);
Conexion::cerrar_conexion();
Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#caja_items');
?>
