<?php
header('Content-Type: application/json');
$archivo = str_replace('%20', ' ', $archivo);
$archivo = str_replace('%23', '#', $archivo);
//if(
  unlink($_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq . '/' . $archivo);
//){
  //Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq);
//}
echo json_encode(array(
  'result'=> '1'
));
?>
