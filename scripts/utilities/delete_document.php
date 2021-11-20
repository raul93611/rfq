<?php
header('Content-Type: application/json');
$archivo = str_replace('%20', ' ', $archivo);
$archivo = str_replace('%23', '#', $archivo);
unlink($_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq . '/' . $archivo);
Conexion::abrir_conexion();
AuditTrailRepository::document_updated(Conexion::obtener_conexion(), 'deleted', $archivo, $id_rfq);
Conexion::cerrar_conexion();
echo json_encode(array(
  'result'=> '1'
));
?>
