<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$item = new Item('', $_POST['id_rfq'], $_SESSION['user']->obtener_id(), 0, $_POST['brand'], $_POST['brand_project'], $_POST['part_number'], $_POST['part_number_project'], htmlspecialchars($_POST['description']), htmlspecialchars($_POST['description_project']), $_POST['quantity'], 0, 0, $_POST['comments'], $_POST['website'], '', null);
$id = RepositorioItem::insertar_item(Conexion::obtener_conexion(), $item);
AuditTrailRepository::create_audit_trail_item_created(Conexion::obtener_conexion(), $id, 'Item', $_POST['part_number_project'], 'Part Number',  $_POST['id_rfq']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'data'=> 'success'
));
