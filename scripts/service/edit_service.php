<?php
if (isset($_POST['edit_service_button'])) {
  try {
    // Sanitize input data
    $id_service = filter_var($_POST['id_service'], FILTER_SANITIZE_NUMBER_INT);
    $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
    $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $unit_price = filter_var($_POST['unit_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $total_price = $quantity * $unit_price;

    // Open connection
    Conexion::abrir_conexion();
    $connection = Conexion::obtener_conexion();

    // Edit service
    $isEdited = ServiceRepository::edit_service($connection, $id_service, $description, $quantity, $unit_price, $total_price);

    // Close connection
    Conexion::cerrar_conexion();

    if ($isEdited) {
      Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq'] . '#service' . $id_service);
    } else {
      throw new Exception('Failed to edit service.');
    }
  } catch (Exception $e) {
    if (isset($connection)) {
      Conexion::cerrar_conexion();
    }
    // Handle the error (e.g., log it, display a message, etc.)
    die("Error: " . $e->getMessage());
  }
}
