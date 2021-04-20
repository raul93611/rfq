<?php
session_start();
if(isset($_POST['guardar_subitem'])){
  Conexion::abrir_conexion();
  $subitem = new Subitem('', $_POST['id_item'], 0, $_POST['brand'], $_POST['brand_project'], $_POST['part_number'], $_POST['part_number_project'], htmlspecialchars($_POST['description']), htmlspecialchars($_POST['description_project']), $_POST['quantity'], 0, 0, $_POST['comments'], $_POST['website'], '', null);
  $id = RepositorioSubitem::insertar_subitem(Conexion::obtener_conexion(), $subitem);
  $item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem-> obtener_id_item());
  $id_rfq = $item-> obtener_id_rfq();
  AuditTrailRepository::create_audit_trail_subitem_created(Conexion::obtener_conexion(), $id, 'Subitem', $_POST['part_number_project'], 'Part Number', $id_rfq);
  Conexion::cerrar_conexion();
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#subitem' . $id);
}
?>
