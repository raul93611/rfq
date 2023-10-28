<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$provider = new Provider('', $_POST['id_item'], $_POST['provider'], $_POST['price']);
RepositorioProvider::insertar_provider(Conexion::obtener_conexion(), $provider);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $provider->obtener_id_item());
AuditTrailRepository::create_audit_trail_item_created(
  Conexion::obtener_conexion(),
  $_POST['id_item'],
  'Provider',
  $_POST['provider'],
  'Provider',
  $item->obtener_id_rfq()
);
RepositorioItem::updateMinorProvider(Conexion::obtener_conexion(), $_POST['id_item']);
RepositorioItem::updateItemPrice(Conexion::obtener_conexion(), $_POST['id_item']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id' => $item->obtener_id_rfq()
));
