<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
RepositorioProvider::actualizar_provider(Conexion::obtener_conexion(), $_POST['id_provider'], $_POST['provider'], $_POST['price']);
$provider = RepositorioProvider::obtener_provider_por_id(Conexion::obtener_conexion(), $_POST['id_provider']);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $provider->obtener_id_item());
AuditTrailRepository::edit_provider_item_events(Conexion::obtener_conexion(), $_POST['provider'], $_POST['provider_original'], $_POST['price'], $_POST['price_original'], $item->obtener_id(), $item->obtener_id_rfq());
RepositorioProvider::setSelectedProvider($provider->obtener_id_item());
Conexion::cerrar_conexion();
echo json_encode(array(
  'id' => $item->obtener_id_rfq()
));
