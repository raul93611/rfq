<?php
try {
  // Open the connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Call the cleanup function to delete folders without RFQ
  RepositorioRfq::cleanUpRfqFolders($conexion);

  // Close the connection
  Conexion::cerrar_conexion();
} catch (Exception $e) {
  // Close the connection if open
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }

  // Print the exception message
  print "Error: " . $e->getMessage();
}
