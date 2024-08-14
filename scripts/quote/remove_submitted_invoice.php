<?php
if (!$id_rfq) {
  die('Error: Required parameter id_rfq is missing.');
}

try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Perform the remove submitted invoice operation
  RepositorioRfq::remove_submitted_invoice($conexion, $id_rfq);

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
