<?php
if (isset($_POST['guardar_cambios_provider'])) {
  // Sanitize and validate inputs
  $id_provider = filter_input(INPUT_POST, 'id_provider', FILTER_VALIDATE_INT);
  $id_rfq = filter_input(INPUT_POST, 'id_rfq', FILTER_VALIDATE_INT);
  $provider = filter_input(INPUT_POST, 'provider', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

  // Original values for audit trail (not sanitized as they are for comparison)
  $provider_original = $_POST['provider_original'];
  $price_original = $_POST['price_original'];

  // Validate required inputs
  if ($id_provider && $id_rfq && $provider && $price !== false) {
    try {
      // Open database connection
      Conexion::abrir_conexion();
      $conexion = Conexion::obtener_conexion();

      // Update provider
      $provider_editado = RepositorioProvider::actualizar_provider($conexion, $id_provider, $provider, $price);

      // Retrieve updated provider and associated item
      $provider_object = RepositorioProvider::obtener_provider_por_id($conexion, $id_provider);
      $item = RepositorioItem::obtener_item_por_id($conexion, $provider_object->obtener_id_item());

      // Create audit trail
      AuditTrailRepository::edit_provider_item_events(
        $conexion,
        $provider,
        $provider_original,
        $price,
        $price_original,
        $item->obtener_id(),
        $id_rfq
      );

      // Close the database connection
      Conexion::cerrar_conexion();

      if ($provider_editado) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
          header('Content-Type: application/json');
          echo json_encode(['success' => true]);
        } else {
          Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#item' . $item->obtener_id());
        }
      } else {
        throw new Exception('Failed to update the provider.');
      }
    } catch (Exception $e) {
      if (isset($conexion)) { Conexion::cerrar_conexion(); }
      if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
      } else {
        echo 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
      }
    }
  } else {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Missing or invalid required fields.']);
    } else {
      echo 'Error: Missing or invalid required fields.';
    }
  }
}
