<?php
header('Content-Type: application/json');

try {
  // Validate and retrieve POST parameters
  $partial_invoice = isset($_POST["partial_invoice"]) ? (bool)$_POST["partial_invoice"] : false;
  $id = isset($_POST['id']) ? $_POST['id'] : null;
  $invoice_acceptance = isset($_POST["invoice_acceptance"]) ? $_POST["invoice_acceptance"] : null;

  if ($id === null || $invoice_acceptance === null) {
    throw new InvalidArgumentException('Invalid inputs provided.');
  }

  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Update invoice acceptance based on the partial_invoice flag
  if ($partial_invoice) {
    InvoiceRepository::updateInvoiceAcceptance($conexion, $id, $invoice_acceptance);
  } else {
    RepositorioRfq::updateInvoiceAcceptance($conexion, $id, $invoice_acceptance);
  }

  // Close the database connection
  Conexion::cerrar_conexion();

  // Prepare and send the success response
  $response = array('response' => 'success');
  echo json_encode($response);
} catch (InvalidArgumentException $e) {
  // Handle validation exceptions
  http_response_code(400);
  $response = array('response' => 'error', 'message' => $e->getMessage());
  echo json_encode($response);
} catch (Exception $e) {
  // Handle general exceptions
  http_response_code(500);
  $response = array('response' => 'error', 'message' => 'An error occurred while processing your request.');
  echo json_encode($response);
} finally {
  // Ensure the database connection is closed if it was opened
  if (class_exists('Conexion') && method_exists('Conexion', 'cerrar_conexion')) {
    Conexion::cerrar_conexion();
  }
}
