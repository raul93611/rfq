<?php
try {
  Conexion::abrir_conexion();
  $connection = Conexion::obtener_conexion();

  $service = ServiceRepository::get_service($connection, $id_service);
  if ($service) {
    $duplicated_service = new Service('', $service->get_id_rfq(), $service->get_description(), $service->get_quantity(), $service->get_unit_price(), $service->get_total_price(), null);
    $id = ServiceRepository::store_service($connection, $duplicated_service);

    if ($id) {
      Conexion::cerrar_conexion();
      Redireccion::redirigir(EDITAR_COTIZACION . '/' . $service->get_id_rfq() . '#service' . $id);
      exit;
    } else {
      throw new Exception('Failed to store duplicated service.');
    }
  } else {
    throw new Exception('Service not found.');
  }
} catch (Exception $e) {
  if (isset($connection)) {
    Conexion::cerrar_conexion();
  }
  // Handle the error (e.g., log it, display a message, etc.)
  die("Error: " . $e->getMessage());
}
