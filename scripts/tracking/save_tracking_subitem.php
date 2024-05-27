<?php
if (isset($_POST['save_tracking_subitem'])) {
  try {
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    // Fetch subitem and item details
    $subitem = RepositorioSubitem::obtener_subitem_por_id($conexion, $_POST['id_subitem']);
    $item = RepositorioItem::obtener_item_por_id($conexion, $subitem->obtener_id_item());

    // Create new tracking subitem
    $tracking = new TrackingSubitem(
      '',
      $_POST['id_subitem'],
      $_POST['quantity_shipped'],
      $_POST['carrier'],
      htmlspecialchars($_POST['tracking_number']),
      $_POST['delivery_date'],
      $_POST['due_date'],
      $_POST['signed_by'],
      htmlspecialchars($_POST['comments'])
    );

    // Insert tracking subitem
    if (TrackingSubitemRepository::insert_tracking($conexion, $tracking)) {
      // Close connection and redirect
      Conexion::cerrar_conexion();
      Redireccion::redirigir(TRACKING . $item->obtener_id_rfq());
    } else {
      throw new Exception('Failed to insert tracking subitem.');
    }
  } catch (Exception $ex) {
    // Handle exceptions and close connection
    if (isset($conexion)) {
      Conexion::cerrar_conexion();
    }
    // Optionally, you can log the error message or handle it as needed
    die('Error: ' . $ex->getMessage());
  }
}
