<?php
// Function to delete provider subitem and create audit trail
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
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Delete provider subitem and create audit trail
  $id_rfq = deleteProviderSubitemAndCreateAuditTrail($conexion, $id_provider_subitem);

  // Close database connection
  Conexion::cerrar_conexion();

  // Redirect to the updated page
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#subitem' . $id_provider_subitem);
} catch (Exception $e) {
  // Ensure the connection is closed
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }
  // Print the error message
  echo 'Error: ' . $e->getMessage();
  exit;
}
