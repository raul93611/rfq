<?php
// Function to create audit trail for provider deletion
function createAuditTrail($conexion, $provider_name, $item_id, $id_rfq) {
  AuditTrailRepository::create_audit_trail_item_provider_deleted(
    $conexion,
    $provider_name,
    'Provider',
    $item_id,
    $id_rfq
  );
}

// Main logic
try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Get provider and related item
  $provider = RepositorioProvider::obtener_provider_por_id($conexion, $id_provider);
  $item = RepositorioItem::obtener_item_por_id($conexion, $provider->obtener_id_item());

  // Delete provider
  $deleted_provider = RepositorioProvider::delete_provider($conexion, $id_provider);

  if ($deleted_provider) {
    // Create audit trail
    createAuditTrail($conexion, $provider->obtener_provider(), $item->obtener_id(), $item->obtener_id_rfq());

    // Close database connection
    Conexion::cerrar_conexion();

    // Redirect to the updated page
    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $item->obtener_id_rfq() . '#item' . $item->obtener_id());
  } else {
    throw new Exception('Failed to delete provider.');
  }
} catch (Exception $e) {
  // Ensure the connection is closed
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }
  // Print the error message
  echo 'Error: ' . $e->getMessage();
  exit;
}
