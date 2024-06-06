<?php
if (isset($_POST['edit_service_button'])) {
  // Sanitize input data
  $id_service = filter_var($_POST['id_service'], FILTER_SANITIZE_NUMBER_INT);
  $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
  $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $unit_price = filter_var($_POST['unit_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $total_price = $quantity * $unit_price;
  $id_rfq = filter_var($_POST['id_rfq'], FILTER_SANITIZE_NUMBER_INT);

  try {
    // Open database connection
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    // Edit the service
    ReQuoteServiceRepository::edit_service(
      $conexion,
      $id_service,
      $description,
      $quantity,
      $unit_price,
      $total_price
    );
  } catch (Exception $e) {
    // Handle exceptions gracefully
    die('Error: ' . $e->getMessage());
  } finally {
    // Ensure the connection is closed
    Conexion::cerrar_conexion();
  }

  // Redirect to the updated service page
  Redireccion::redirigir(RE_QUOTE . $id_rfq . '#service' . $id_service);
}
