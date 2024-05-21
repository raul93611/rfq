<?php
header('Content-Type: application/json');
unlink($_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq . '/' . urldecode($archivo));
Conexion::abrir_conexion();
AuditTrailRepository::document_updated(Conexion::obtener_conexion(), 'deleted', $archivo, $id_rfq);
Conexion::cerrar_conexion();
echo json_encode(array(
  'result' => '1'
));