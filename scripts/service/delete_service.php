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

  $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
  if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
  } else {
    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $service->get_id_rfq() . '#services_table');
  }
} catch (Exception $e) {
  $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
  if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred.']);
  } else {
    die("Error: An unexpected error occurred. Please try again later.");
  }
} finally {
  Conexion::cerrar_conexion();
}
