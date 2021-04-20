<?php
session_start();
if (isset($_POST['guardar_item'])) {
  Conexion::abrir_conexion();
  $item = new Item('', $_POST['id_rfq'], $_SESSION['id_usuario'], 0, $_POST['brand'], $_POST['brand_project'], $_POST['part_number'], $_POST['part_number_project'], htmlspecialchars($_POST['description']), htmlspecialchars($_POST['description_project']), $_POST['quantity'], 0, 0, $_POST['comments'], $_POST['website'], '', null);
  $id = RepositorioItem::insertar_item(Conexion::obtener_conexion(), $item);
  AuditTrailRepository::create_audit_trail_item_created(Conexion::obtener_conexion(), $id, 'Item', $_POST['part_number_project'], 'Part Number',  $_POST['id_rfq']);
  Conexion::cerrar_conexion();
  if($id){
    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq'] . '#item' . $id);
  }
}
?>
