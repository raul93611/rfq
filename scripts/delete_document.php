<?php
$archivo = str_replace('%20', ' ', $archivo);
$archivo = str_replace('%23', '#', $archivo);
//if(
  unlink($_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq . '/' . $archivo);
//){
  //Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq);
//}
echo 0;
?>
