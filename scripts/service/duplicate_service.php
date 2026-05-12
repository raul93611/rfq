<?php
try {
  Conexion::abrir_conexion();
  $connection = Conexion::obtener_conexion();

  $service = ServiceRepository::get_service($connection, $id_service);
  if ($service) {
    $duplicated_service = new Service('', $service->get_id_rfq(), $service->get_description(), $service->get_quantity(), $service->get_unit_price(), $service->get_total_price(), null, $service->getIdRoom());
    $id = ServiceRepository::store_service($connection, $duplicated_service);

    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    if ($id) {
      Conexion::cerrar_conexion();
      if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
      } else {
        Redireccion::redirigir(EDITAR_COTIZACION . '/' . $service->get_id_rfq() . '#service' . $id);
      }
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
  $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
  if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
  } else {
    die("Error: " . $e->getMessage());
  }
}
