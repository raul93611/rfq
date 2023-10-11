<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$provider = RepositorioProvider::obtener_provider_por_id(Conexion::obtener_conexion(), $_POST['id']);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $provider->obtener_id_item());
RepositorioProvider::delete_provider(Conexion::obtener_conexion(), $_POST['id']);
AuditTrailRepository::create_audit_trail_item_provider_deleted(Conexion::obtener_conexion(), $provider->obtener_provider(), 'Provider', $item->obtener_id(), $item->obtener_id_rfq());
RepositorioProvider::setSelectedProvider($provider->obtener_id_item());
Conexion::cerrar_conexion();
echo json_encode(array(
  'id'=> $item->obtener_id_rfq()
));