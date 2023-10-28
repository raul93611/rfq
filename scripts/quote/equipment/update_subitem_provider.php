<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
RepositorioProviderSubitem::actualizar_provider_subitem(Conexion::obtener_conexion(), $_POST['id_subitem_provider'], $_POST['provider'], $_POST['price']);
$provider_subitem = RepositorioProviderSubitem::obtener_provider_subitem_por_id(Conexion::obtener_conexion(), $_POST['id_subitem_provider']);
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $provider_subitem->obtener_id_subitem());
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem->obtener_id_item());
AuditTrailRepository::edit_provider_subitem_events(Conexion::obtener_conexion(), $_POST['provider'], $_POST['provider_original'], $_POST['price'], $_POST['price_original'], $subitem->obtener_id(), $item->obtener_id_rfq());
RepositorioSubitem::updateMinorProvider(Conexion::obtener_conexion(), $provider_subitem->obtener_id_subitem());
RepositorioSubitem::updateSubitemPrice(Conexion::obtener_conexion(), $provider_subitem->obtener_id_subitem());
Conexion::cerrar_conexion();
echo json_encode(array(
  'id' => $item->obtener_id_rfq()
));
