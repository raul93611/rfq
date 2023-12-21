<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
MonthlyProjectionRepository::update(Conexion::obtener_conexion(), $_POST['projected_amount'], $_POST['id_monthly_projection']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'reponse' => 'success'
));
