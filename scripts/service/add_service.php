<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service_button'])) {
  try {
    // Validate and sanitize input
    $id_rfq = filter_input(INPUT_POST, 'id_rfq', FILTER_VALIDATE_INT);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_FLOAT);
    $unit_price = filter_input(INPUT_POST, 'unit_price', FILTER_VALIDATE_FLOAT);
    $id_room = empty($_POST['id_room']) ? null : htmlspecialchars($_POST['id_room']);

    if ($id_rfq === false || $description === false || $quantity === false || $unit_price === false) {
      throw new InvalidArgumentException("Invalid input data.");
    }

    // Calculate total price
    $total_price = $quantity * $unit_price;

    // Create new Service object
    $service = new Service('', $id_rfq, $description, $quantity, $unit_price, $total_price, null, $id_room);

    // Open database connection
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    // Store service
    $id = ServiceRepository::store_service($conexion, $service);

    // Close database connection
    Conexion::cerrar_conexion();

    // Redirect to the edit quote page with the newly added service
    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#service' . $id);
  } catch (InvalidArgumentException $e) {
    // Handle validation exceptions
    // Implement appropriate error handling (e.g., redirect to an error page, log the error, etc.)
    die("Error: " . $e->getMessage());
  } catch (Exception $e) {
    // Handle other exceptions
    // Implement appropriate error handling (e.g., redirect to an error page, log the error, etc.)
    die("An error occurred: " . $e->getMessage());
  }
}
