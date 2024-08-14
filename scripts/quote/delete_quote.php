<?php
try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch the quote
  $quote = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);

  // Delete the quote
  RepositorioRfq::delete_quote($conexion, $quote->obtener_id());

  // Create audit trail
  AuditTrailRepository::quote_status_audit_trail($conexion, 'Deleted', $id_rfq);

  // Close the database connection
  Conexion::cerrar_conexion();

  // Redirect based on the quote status
  if ($quote->isNobid()) {
    Redireccion::redirigir(NO_BID);
  } elseif ($quote->isNotSubmitted()) {
    Redireccion::redirigir(NO_SUBMITTED);
  } elseif ($quote->isCancelled()) {
    Redireccion::redirigir(CANCELLED);
  } else {
    Redireccion::redirigir(CHANNEL . $quote->obtener_canal());
  }
} catch (Exception $e) {
  // Ensure the connection is closed in case of an error
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }
  // Print the error message
  echo 'Error: ' . $e->getMessage();
  exit;
}
