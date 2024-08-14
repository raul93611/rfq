<?php
try {
  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch tracking by ID
  $tracking = TrackingRepository::get_tracking_by_id($conexion, $id_tracking);
  if (!$tracking) {
    throw new Exception('Tracking not found');
  }

  // Fetch related item
  $item = RepositorioItem::obtener_item_por_id($conexion, $tracking->get_id_item());
  if (!$item) {
    throw new Exception('Item not found');
  }

  // Delete the tracking
  $deleted = TrackingRepository::delete_tracking($conexion, $id_tracking);
  if (!$deleted) {
    throw new Exception('Failed to delete tracking');
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
