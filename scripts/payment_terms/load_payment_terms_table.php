<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$payment_terms = PaymentTermRepository::get_all(Conexion::obtener_conexion());
Conexion::cerrar_conexion();
$json = [];
foreach ($payment_terms as $key => $payment_term) {
  $row = [
    $payment_term-> get_payment_term(),
    '<button type="button" data="' . $payment_term-> get_id() . '" class="edit_button btn btn-info btn-sm" name=""><i class="fas fa-pen"></i></button>
    <button type="button" data="' . $payment_term-> get_id() . '" class="delete_button btn btn-danger btn-sm" name=""><i class="fas fa-trash"></i></button>'
  ];
  array_push($json, $row);
}
echo json_encode(array(
  'data' => $json
));
?>
