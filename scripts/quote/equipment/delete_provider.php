<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$provider = RepositorioProvider::obtener_provider_por_id(Conexion::obtener_conexion(), $_POST['id']);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $provider->obtener_id_item());
RepositorioProvider::delete_provider(Conexion::obtener_conexion(), $_POST['id']);
AuditTrailRepository::create_audit_trail_item_provider_deleted(Conexion::obtener_conexion(), $provider->obtener_provider(), 'Provider', $item->obtener_id(), $item->obtener_id_rfq());
RepositorioItem::updateMinorProvider(Conexion::obtener_conexion(), $item->obtener_id());
RepositorioItem::updateItemPrice(Conexion::obtener_conexion(), $item->obtener_id());
Conexion::cerrar_conexion();
echo json_encode(array(
  'id' => $item->obtener_id_rfq()
));
