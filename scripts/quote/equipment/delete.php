<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $_POST["id"]);
$id_rfq = $item-> obtener_id_rfq();
$subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $_POST["id"]);
if(count($subitems)){
  foreach ($subitems as $subitem) {
    RepositorioSubitem::delete_subitem(Conexion::obtener_conexion(), $subitem-> obtener_id());
  }
}
AuditTrailRepository::create_audit_trail_item_deleted(Conexion::obtener_conexion(), 'Item', $item-> obtener_part_number_project(), 'Part Number', $id_rfq);
RepositorioItem::delete_item(Conexion::obtener_conexion(), $_POST["id"]);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id'=> $id_rfq
));