<?php
try {
  if (!isset($id_rfq) || !is_numeric($id_rfq)) {
    throw new Exception('Invalid or missing RFQ ID');
  }

  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  RepositorioRfq::mark_unmark_as_pending($conexion, $id_rfq, 0);

  Conexion::cerrar_conexion();
  Redireccion::redirigir(FULFILLMENT . $id_rfq);
} catch (Exception $e) {
  // Handle exceptions (e.g., log the error or display an error message)
  if (Conexion::obtener_conexion()) {
    Conexion::cerrar_conexion();
  }

  die('ERROR: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
