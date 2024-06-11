<?php
// Function to delete subitems by item ID
function deleteSubitems($conexion, $id_item) {
  $subitems = RepositorioSubitem::obtener_subitems_por_id_item($conexion, $id_item);
  if (count($subitems)) {
    foreach ($subitems as $subitem) {
      RepositorioSubitem::delete_subitem($conexion, $subitem->obtener_id());
    }
  }
}

// Function to create audit trail for item deletion
function createAuditTrail($conexion, $item, $id_rfq) {
  AuditTrailRepository::create_audit_trail_item_deleted(
    $conexion,
    'Item',
    $item->obtener_part_number_project(),
    'Part Number',
    $id_rfq
  );
}

// Function to delete an item by ID
function deleteItem($conexion, $id_item) {
  $item = RepositorioItem::obtener_item_por_id($conexion, $id_item);
  $id_rfq = $item->obtener_id_rfq();
  deleteSubitems($conexion, $id_item);
  createAuditTrail($conexion, $item, $id_rfq);
  RepositorioItem::delete_item($conexion, $id_item);
  return $id_rfq;
}

try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Delete item and get related RFQ ID
  $id_rfq = deleteItem($conexion, $id_item);

  // Close database connection
  Conexion::cerrar_conexion();

  // Redirect to the updated page
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#caja_items');
} catch (Exception $e) {
  // Ensure the connection is closed
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }
  // Print the error message
  echo 'Error: ' . $e->getMessage();
  exit;
}
