<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
InvoiceRepository::delete(Conexion::obtener_conexion(), $_POST["id"]);
Conexion::cerrar_conexion();
echo json_encode(array(
  'response' => 'success'
));