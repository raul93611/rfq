<?php
session_start();
if(isset($_POST['guardar_subitem'])){
  Database::open_connection();
  $subitem = new Subitem('', $_POST['id_item'], 0, $_POST['brand'], $_POST['brand_project'], $_POST['part_number'], $_POST['part_number_project'], htmlspecialchars($_POST['description']), htmlspecialchars($_POST['description_project']), $_POST['quantity'], 0, 0, $_POST['comments'], $_POST['website'], '', null);
  $id = RepositorioSubitem::insertar_subitem(Database::get_connection(), $subitem);
  $item = RepositorioItem::obtener_item_por_id(Database::get_connection(), $subitem-> obtener_id_item());
  $id_rfq = $item-> obtener_id_rfq();
  AuditTrailRepository::create_audit_trail_subitem_created(Database::get_connection(), $id, 'Subitem', $_POST['part_number_project'], 'Part Number', $id_rfq);
  Database::close_connection();
  Redireccion::redirigir(EDIT_QUOTE . '/' . $id_rfq . '#subitem' . $id);
}
?>
