<?php
function createAuditTrail($conexion, $provider_name, $item_id, $id_rfq) {
  AuditTrailRepository::create_audit_trail_item_provider_deleted(
    $conexion,
    $provider_name,
    'Provider',
    $item_id,
    $id_rfq
  );
}

try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  $provider = RepositorioProvider::obtener_provider_por_id($conexion, $id_provider);
  $item = RepositorioItem::obtener_item_por_id($conexion, $provider->obtener_id_item());

  $deleted_provider = RepositorioProvider::delete_provider($conexion, $id_provider);

  if ($deleted_provider) {
    createAuditTrail($conexion, $provider->obtener_provider(), $item->obtener_id(), $item->obtener_id_rfq());
    Conexion::cerrar_conexion();

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
      header('Content-Type: application/json');
      echo json_encode(['success' => true, 'id_rfq' => $item->obtener_id_rfq()]);
    } else {
      Redireccion::redirigir(EDITAR_COTIZACION . '/' . $item->obtener_id_rfq() . '#item' . $item->obtener_id());
    }
  } else {
    throw new Exception('Failed to delete provider.');
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
