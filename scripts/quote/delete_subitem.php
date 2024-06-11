<?php
try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch the subitem and related item
  $subitem = RepositorioSubitem::obtener_subitem_por_id($conexion, $id_subitem);
  $item = RepositorioItem::obtener_item_por_id($conexion, $subitem->obtener_id_item());
  $id_rfq = $item->obtener_id_rfq();

  // Delete the subitem
  RepositorioSubitem::delete_subitem($conexion, $id_subitem);

  // Create audit trail for the deletion
  AuditTrailRepository::create_audit_trail_item_deleted(
    $conexion,
    'Subitem',
    $subitem->obtener_part_number_project(),
    'Part Number',
    $id_rfq
  );

  // Close the database connection
  Conexion::cerrar_conexion();

  // Redirect to the updated page
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#caja_items');
} catch (Exception $e) {
  // Ensure the connection is closed in case of an error
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }
  // Print the error message
  echo 'Error: ' . $e->getMessage();
  exit;
}
