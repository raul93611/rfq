<?php
header('Content-Type: application/json');
$payment_term = PaymentTermRepository::validate_payment_term($_POST);
if(!$payment_term){
  $result = false;
}else{
  $result = true;
  Conexion::abrir_conexion();
  PaymentTermRepository::insert(Conexion::obtener_conexion(), $payment_term);
  Conexion::cerrar_conexion();
}
echo json_encode(array(
  'result' => $result,
));
?>
