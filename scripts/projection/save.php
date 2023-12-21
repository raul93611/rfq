<?php
header('Content-Type: application/json');
$success = false;
Conexion::abrir_conexion();
if (!YearlyProjectionRepository::projectionExists(Conexion::obtener_conexion())) {
  YearlyProjectionRepository::save(Conexion::obtener_conexion());
  $success = true;
}
Conexion::cerrar_conexion();
echo json_encode(array(
  'data' => $success
));
