<?php
if (isset($_POST['save_tracking'])) {
  try {
    // Open the connection
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    // Fetch the item by ID
    $item = RepositorioItem::obtener_item_por_id($conexion, $_POST['id_item']);
    if (!$item) {
      throw new Exception('Item not found.');
    }

    // Create a new tracking object
    $tracking = new Tracking(
      '',
      $_POST['id_item'],
      $_POST['quantity_shipped'],
      $_POST['carrier'],
      htmlspecialchars($_POST['tracking_number']),
      $_POST['delivery_date'],
      $_POST['due_date'],
      $_POST['signed_by'],
      htmlspecialchars($_POST['comments'])
    );

    // Insert the tracking record
    $success = TrackingRepository::insert_tracking($conexion, $tracking);
    if (!$success) {
      throw new Exception('Failed to insert tracking record.');
    }

    // Close the connection
    Conexion::cerrar_conexion();

    // Redirect on success
    Redireccion::redirigir(TRACKING . $item->obtener_id_rfq());
  } catch (Exception $ex) {
    // Ensure the connection is closed in case of error
    if (isset($conexion)) {
      Conexion::cerrar_conexion();
    }
    // Log or handle the error
    die('Error: ' . $ex->getMessage());
  }
}
