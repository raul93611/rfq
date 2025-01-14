<?php
try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  RepositorioRfq::destroyQuote($conexion, $id_rfq);

  // Close the database connection
  Conexion::cerrar_conexion();

  // Redirect based on the quote status
  Redireccion::redirigir(DELETED);
} catch (Exception $e) {
  // Ensure the connection is closed in case of an error
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }
  // Print the error message
  echo 'Error: ' . $e->getMessage();
  exit;
}
