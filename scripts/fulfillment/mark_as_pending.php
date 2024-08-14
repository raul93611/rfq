<?php
try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Mark or unmark the RFQ as pending
  RepositorioRfq::mark_unmark_as_pending($conexion, $id_rfq, 1);

  // Close the database connection
  Conexion::cerrar_conexion();

  // Redirect to the fulfillment page
  Redireccion::redirigir(FULFILLMENT . $id_rfq);
} catch (Exception $e) {
  // Ensure the database connection is closed in case of an error
  if (Conexion::obtener_conexion()) {
    Conexion::cerrar_conexion();
  }

  // Handle the error (e.g., log it, display an error message, etc.)
  die('Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
