<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
PaymentTermRepository::delete(Conexion::obtener_conexion(), $_POST['id_payment_term']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'result' => true
));
?>
