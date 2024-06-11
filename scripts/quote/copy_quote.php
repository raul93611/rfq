<?php
// Function to copy RFQ and related data
function copyRfqData($conexion, $id_rfq) {
  $id_rfq_copia = RepositorioRfq::copyRfq($conexion, $id_rfq);
  RepositorioRfq::copyRfqFiles($id_rfq, $id_rfq_copia);
  RepositorioRfq::copyItems($conexion, $id_rfq, $id_rfq_copia);
  return $id_rfq_copia;
}

try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Copy RFQ and related data
  $id_rfq_copia = copyRfqData($conexion, $id_rfq);

  // Redirect to the updated page
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq_copia);
} catch (Exception $e) {
  // Error handling
  echo 'Error: ' . $e->getMessage();
} finally {
  // Ensure the connection is closed
  Conexion::cerrar_conexion();
}
