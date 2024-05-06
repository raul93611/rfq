<?php
header('Content-Type: application/json');

Conexion::abrir_conexion();
$typeOfContractData = YearlyProjectionRepository::getTypeOfContractChart(Conexion::obtener_conexion(), $_POST["id"]);
Conexion::cerrar_conexion();

$response = array(
  "typeOfContractData" => $typeOfContractData
);

echo json_encode($response);
