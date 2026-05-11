<?php
if (isset($_POST['guardar_provider'])) {
  // Sanitize and validate inputs
  $id_item = filter_input(INPUT_POST, 'id_item', FILTER_VALIDATE_INT);
  $provider = filter_input(INPUT_POST, 'provider', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

  // Validate required inputs
  if ($id_item && $provider && $price !== false) {
    try {
      // Open database connection
      Conexion::abrir_conexion();
      $conexion = Conexion::obtener_conexion();

      // Create provider
      $provider_obj = new Provider('', $id_item, $provider, $price);
      $provider_insertado = RepositorioProvider::insertar_provider($conexion, $provider_obj);

      // Retrieve item information
      $item = RepositorioItem::obtener_item_por_id($conexion, $id_item);
      $id_rfq = $item->obtener_id_rfq();

      // Create audit trail
      AuditTrailRepository::create_audit_trail_item_created(
        $conexion,
        $id_item,
        'Provider',
        $provider,
        'Provider',
        $id_rfq
      );

      // Close the database connection
      Conexion::cerrar_conexion();

      if ($provider_insertado) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
          header('Content-Type: application/json');
          echo json_encode(['success' => true]);
        } else {
          Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#item' . $id_item);
        }
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
