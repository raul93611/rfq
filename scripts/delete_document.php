<?php
header('Content-Type: application/json');
$archivo = str_replace('%20', ' ', $archivo);
$archivo = str_replace('%23', '#', $archivo);
unlink($_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq . '/' . $archivo);
echo json_encode(array(
  'result'=> '1'
));
?>
