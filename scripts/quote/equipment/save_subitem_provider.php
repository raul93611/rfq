<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$provider_subitem = new ProviderSubitem('', $_POST['id_subitem'], $_POST['provider'], $_POST['price']);
RepositorioProviderSubitem::insertar_provider_subitem(Conexion::obtener_conexion(), $provider_subitem);
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $provider_subitem->obtener_id_subitem());
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem->obtener_id_item());
AuditTrailRepository::create_audit_trail_subitem_created(Conexion::obtener_conexion(), $_POST['id_subitem'], 'Provider', $_POST['provider'], 'Provider', $item->obtener_id_rfq());
RepositorioSubitem::updateMinorProvider(Conexion::obtener_conexion(), $_POST['id_subitem']);
RepositorioSubitem::updateSubitemPrice(Conexion::obtener_conexion(), $_POST['id_subitem']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id' => $item->obtener_id_rfq()
));