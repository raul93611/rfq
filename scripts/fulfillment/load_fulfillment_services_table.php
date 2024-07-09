<?php
try {
  // Open database connection
  Conexion::abrir_conexion();

  // Fetch and process the list of services for the given RFQ ID
  FulfillmentRepository::services_list(Conexion::obtener_conexion(), $id_rfq);
} catch (Exception $e) {
  // Optionally handle the error, such as redirecting to an error page or showing a user-friendly message
  // e.g., Redireccion::redirigir(ERROR_PAGE);
  die('Error fetching services list: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
} finally {
  // Ensure the database connection is closed
  Conexion::cerrar_conexion();
}
