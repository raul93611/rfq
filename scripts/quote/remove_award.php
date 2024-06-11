<?php
// Ensure necessary variables are set
if (!isset($id_rfq)) {
  die('Error: RFQ ID is not set.');
}

try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Perform the remove award operation
  RepositorioRfq::remove_award($conexion, $id_rfq);

  // Close database connection
  Conexion::cerrar_conexion();

  // Redirect to the specified URL
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq);
} catch (Exception $e) {
  // Handle any exceptions that occur
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }
  die('An error occurred: ' . $e->getMessage());
}
