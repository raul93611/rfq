<?php
header('Content-Type: application/json');

try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch all providers
  $providers = ProviderListRepository::get_all($conexion);

  // Close database connection
  Conexion::cerrar_conexion();

  // Prepare the JSON response
  $json = array_map(function ($provider) {
    return [
      htmlspecialchars($provider->get_company_name()),
      '<button type="button" data="' . htmlspecialchars($provider->get_id()) . '" class="edit_button btn btn-secondary btn-sm" name=""><i class="fas fa-pen"></i></button>
       <button type="button" data="' . htmlspecialchars($provider->get_id()) . '" class="delete_button btn btn-secondary btn-sm" name=""><i class="fas fa-trash"></i></button>'
    ];
  }, $providers);

  // Output the JSON response
  echo json_encode(['data' => $json]);
} catch (Exception $e) {
  // Close the database connection in case of an error
  Conexion::cerrar_conexion();

  // Output the error message as JSON
  echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
  exit;
}
