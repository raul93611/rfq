<?php
$archivo = str_replace('%20', ' ', $archivo);
if(unlink($_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq . '/' . $archivo)){
  Redireccion::redirigir1(EDITAR_COTIZACION . '/' . $id_rfq);
}
?>
