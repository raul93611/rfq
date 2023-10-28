<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$provider_subitem = RepositorioProviderSubitem::obtener_provider_subitem_por_id(Conexion::obtener_conexion(), $_POST["id"]);
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $provider_subitem->obtener_id_subitem());
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem->obtener_id_item());
RepositorioProviderSubitem::delete_provider_subitem(Conexion::obtener_conexion(), $_POST["id"]);
AuditTrailRepository::create_audit_trail_subitem_provider_deleted(Conexion::obtener_conexion(), $provider_subitem->obtener_provider(), 'Provider', $subitem->obtener_id(), $item->obtener_id_rfq());
RepositorioSubitem::updateMinorProvider(Conexion::obtener_conexion(), $provider_subitem->obtener_id_subitem());
RepositorioSubitem::updateSubitemPrice(Conexion::obtener_conexion(), $provider_subitem->obtener_id_subitem());
Conexion::cerrar_conexion();
echo json_encode(array(
  'id' => $item->obtener_id_rfq()
));
