<?php
header('Content-Type: application/json');
$invoice = new Invoice('', $_POST['id_rfq'], $_POST['name'], $_POST["created_at"]);
Conexion::abrir_conexion();
InvoiceRepository::save(Conexion::obtener_conexion(), $invoice);
Conexion::cerrar_conexion();
echo json_encode(array(
  'data'=> 'success'
));