<?php
header('Content-Type: application/json');

// Initialize the error variable
$error = null;

// Open the database connection
Conexion::abrir_conexion();

try {
  // Check if the invoice name is unique
  if (!InvoiceRepository::isNameUnique(Conexion::obtener_conexion(), $_POST["name"])) {
    $error = 'Name is already taken';
  } else {
    // Create a new Invoice object
    $invoice = new Invoice('', $_POST['id_rfq'], $_POST['name'], $_POST["created_at"]);

    // Save the invoice to the database
    $success = InvoiceRepository::save(Conexion::obtener_conexion(), $invoice);

    // If the save operation failed, set an error message
    if (!$success) {
      $error = 'Failed to save the invoice';
    }
  }
} catch (Exception $e) {
  // Catch any exceptions and set the error message
  $error = 'An unexpected error occurred: ' . $e->getMessage();
} finally {
  // Close the database connection
  Conexion::cerrar_conexion();
}

// Return the error (or null) in the response
echo json_encode(array(
  'error' => $error
));
