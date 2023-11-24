<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
InvoiceRepository::update(Conexion::obtener_conexion(), $_POST['name'], $_POST["created_at"], $_POST['id_invoice']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'reponse' => 'success'
));