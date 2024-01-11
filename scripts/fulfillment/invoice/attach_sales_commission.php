<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
InvoiceRepository::attachSalesCommission(Conexion::obtener_conexion(), $_POST['id'], $_POST["idRfq"]);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id_rfq' => $_POST["idRfq"]
));