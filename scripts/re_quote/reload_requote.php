<?php
try {
  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Reload the requote
  ReQuoteRepository::reload_requote($conexion, $id_rfq);
} catch (Exception $e) {
  // Handle any exceptions by displaying an error message and terminating the script
  die('Error: ' . $e->getMessage());
} finally {
  // Ensure the connection is closed
  Conexion::cerrar_conexion();
}

// Redirect to the specified RE_QUOTE page
Redireccion::redirigir(RE_QUOTE . $id_rfq);
