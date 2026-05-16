<?php
if (isset($_POST['edit_service_button'])) {
  try {
    // Sanitize input data
    $id_service = filter_var($_POST['id_service'], FILTER_SANITIZE_NUMBER_INT);
    $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
    $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $unit_price = filter_var($_POST['unit_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $id_room = empty($_POST['id_room']) ? null : htmlspecialchars($_POST['id_room']);
    $total_price = $quantity * $unit_price;

    // Open connection
    Conexion::abrir_conexion();
    $connection = Conexion::obtener_conexion();

    // Edit service
    $isEdited = ServiceRepository::edit_service($connection, $id_service, $description, $quantity, $unit_price, $total_price, $id_room);

    // Close connection
    Conexion::cerrar_conexion();

    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    if ($isEdited) {
      if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
      } else {
        Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq'] . '#service' . $id_service);
      }
    } else {
      throw new Exception('Failed to edit service.');
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
}
