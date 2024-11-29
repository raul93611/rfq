<?php
header('Content-Type: application/json');

$error = null;

try {
  Conexion::abrir_conexion();

  $conexion = Conexion::obtener_conexion();

  if (!InvoiceRepository::isNameEditable($conexion, $_POST["name"], $_POST["id_invoice"])) {
    $error = 'Name is already taken';
  } else {
    $isUpdated = InvoiceRepository::update($conexion, $_POST['name'], $_POST["created_at"], $_POST['id_invoice']);
    if (!$isUpdated) {
      $error = 'Failed to update the invoice.';
    }
  }
} catch (Exception $e) {
  $error = 'An unexpected error occurred: ' . $e->getMessage();
} finally {
  Conexion::cerrar_conexion();
}

echo json_encode([
  'error' => $error,
]);
