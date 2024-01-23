<?php
header('Content-Type: application/json');
$error = null;
Conexion::abrir_conexion();
if (!InvoiceRepository::isNameEditable(Conexion::obtener_conexion(), $_POST["name"], $_POST["id_invoice"])) {
  $error = 'Name is already taken';
} else {
  InvoiceRepository::update(Conexion::obtener_conexion(), $_POST['name'], $_POST["created_at"], $_POST['id_invoice']);
}
Conexion::cerrar_conexion();
echo json_encode(array(
  'error' => $error
));
