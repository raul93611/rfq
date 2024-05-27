<?php
try {
  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch tracking subitem by ID
  $tracking_subitem = TrackingSubitemRepository::get_tracking_subitem_by_id($conexion, $id_tracking_subitem);
  if (!$tracking_subitem) {
    throw new Exception('Tracking subitem not found');
  }

  // Fetch related subitem
  $subitem = RepositorioSubitem::obtener_subitem_por_id($conexion, $tracking_subitem->get_id_subitem());
  if (!$subitem) {
    throw new Exception('Subitem not found');
  }

  // Fetch related item
  $item = RepositorioItem::obtener_item_por_id($conexion, $subitem->obtener_id_item());
  if (!$item) {
    throw new Exception('Item not found');
  }

  // Delete the tracking subitem
  $deleted = TrackingSubitemRepository::delete_tracking_subitem($conexion, $id_tracking_subitem);
  if (!$deleted) {
    throw new Exception('Failed to delete tracking subitem');
  }

  // Close the database connection
  Conexion::cerrar_conexion();

  // Redirect to the tracking page for the related RFQ
  Redireccion::redirigir(TRACKING . $item->obtener_id_rfq());
} catch (Exception $e) {
  // Ensure the database connection is closed in case of an error
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }

  // Print the error message
  echo 'Error: ' . $e->getMessage();
}
