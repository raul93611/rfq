<?php
try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Retrieve the service
  $service = ServiceRepository::get_service($conexion, $id_service);

  if (!$service) {
    throw new Exception('Service not found.');
  }

  // Delete the service
  $deleteSuccess = ServiceRepository::delete_service($conexion, $id_service);

  if (!$deleteSuccess) {
    throw new Exception('Failed to delete the service.');
  }

  // Redirect to the edit quote page with the services table anchor
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $service->get_id_rfq() . '#services_table');
} catch (Exception $e) {
  // Display a generic error message to the user
  die("Error: An unexpected error occurred. Please try again later.");
} finally {
  // Ensure the connection is closed
  Conexion::cerrar_conexion();
}
