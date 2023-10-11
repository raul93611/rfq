<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $_POST['id']);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem-> obtener_id_item());
RepositorioSubitem::delete_subitem(Conexion::obtener_conexion(), $_POST['id']);
AuditTrailRepository::create_audit_trail_item_deleted(Conexion::obtener_conexion(), 'Subitem', $subitem-> obtener_part_number_project(), 'Part Number', $item-> obtener_id_rfq());
Conexion::cerrar_conexion();
echo json_encode(array(
  'id'=> $item-> obtener_id_rfq()
));