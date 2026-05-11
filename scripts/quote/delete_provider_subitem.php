<?php
function deleteProviderSubitemAndCreateAuditTrail($conexion, $id_provider_subitem) {
  $provider_subitem = RepositorioProviderSubitem::obtener_provider_subitem_por_id($conexion, $id_provider_subitem);
  $subitem = RepositorioSubitem::obtener_subitem_por_id($conexion, $provider_subitem->obtener_id_subitem());
  $item = RepositorioItem::obtener_item_por_id($conexion, $subitem->obtener_id_item());

  RepositorioProviderSubitem::delete_provider_subitem($conexion, $id_provider_subitem);
  AuditTrailRepository::create_audit_trail_subitem_provider_deleted(
    $conexion,
    $provider_subitem->obtener_provider(),
    'Provider',
    $subitem->obtener_id(),
    $item->obtener_id_rfq()
  );

  return $item->obtener_id_rfq();
}

try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  $id_rfq = deleteProviderSubitemAndCreateAuditTrail($conexion, $id_provider_subitem);

  Conexion::cerrar_conexion();

  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'id_rfq' => $id_rfq]);
  } else {
    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#caja_items');
  }
} catch (Exception $e) {
  if (isset($conexion)) { Conexion::cerrar_conexion(); }
  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
  } else {
    echo 'Error: ' . $e->getMessage();
  }
  exit;
}
