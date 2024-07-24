<?php
try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Copy RFQ and related data
  $id_rfq_copia = RepositorioRfq::copyRfqData($conexion, $id_rfq);

  // Redirect to the updated page
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq_copia);
} catch (Exception $e) {
  // Error handling
  echo 'Error: ' . $e->getMessage();
} finally {
  // Ensure the connection is closed
  Conexion::cerrar_conexion();
}
