<?php
header('Content-Type: application/json');
$error = null;
Conexion::abrir_conexion();
if (!InvoiceRepository::isNameUnique(Conexion::obtener_conexion(), $_POST["name"])) {
  $error = 'Name is already taken';
} else {
  $invoice = new Invoice('', $_POST['id_rfq'], $_POST['name'], $_POST["created_at"]);
  InvoiceRepository::save(Conexion::obtener_conexion(), $invoice);
}
Conexion::cerrar_conexion();
echo json_encode(array(
  'error' => $error
));
