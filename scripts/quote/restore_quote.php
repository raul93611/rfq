<?php
try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Restore the quote
  RepositorioRfq::restore_quote($conexion, $id_rfq);

  // Log the restoration in the audit trail
  AuditTrailRepository::quote_status_audit_trail($conexion, 'Restored', $id_rfq);

  // Close the database connection
  Conexion::cerrar_conexion();

  // Redirect to the deleted quotes page
  Redireccion::redirigir(DELETED);
} catch (Exception $e) {
  // Ensure the connection is closed in case of an error
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }

  // Handle the exception (logging, user feedback, etc.)
  error_log('Error restoring quote: ' . $e->getMessage());
  // Optionally, redirect to an error page or display an error message
  Redireccion::redirigir(ERROR);
}
