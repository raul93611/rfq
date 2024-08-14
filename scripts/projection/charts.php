<?php
header('Content-Type: application/json');
try {
  // Open database connection
  Conexion::abrir_conexion();

  // Validate and sanitize input
  if (isset($_POST["id"]) && is_numeric($_POST["id"])) {
    $id = intval($_POST["id"]);

    // Get type of contract data
    $typeOfContractData = YearlyProjectionRepository::getTypeOfContractChart(Conexion::obtener_conexion(), $id);

    // Close database connection
    Conexion::cerrar_conexion();

    // Prepare response
    $response = array(
      "typeOfContractData" => $typeOfContractData
    );

    // Output response as JSON
    echo json_encode($response);
  } else {
    throw new Exception("Invalid input");
  }
} catch (Exception $e) {
  // Handle exceptions and prepare error response
  $errorResponse = array(
    "error" => $e->getMessage()
  );

  // Output error response as JSON
  echo json_encode($errorResponse);

  // Ensure the database connection is closed in case of an error
  if (Conexion::obtener_conexion()) {
    Conexion::cerrar_conexion();
  }
}
