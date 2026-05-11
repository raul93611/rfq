<?php
if (isset($_POST['guardar_provider_subitem'])) {
  // Sanitize and validate inputs
  $id_subitem = filter_input(INPUT_POST, 'id_subitem', FILTER_VALIDATE_INT);
  $provider = filter_input(INPUT_POST, 'provider', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

  // Validate required inputs
  if ($id_subitem && $provider && $price !== false) {
    try {
      // Open database connection
      Conexion::abrir_conexion();
      $conexion = Conexion::obtener_conexion();

      // Create provider subitem
      $provider_subitem = new ProviderSubitem('', $id_subitem, $provider, $price);
      RepositorioProviderSubitem::insertar_provider_subitem($conexion, $provider_subitem);

      // Retrieve subitem and item information
      $subitem = RepositorioSubitem::obtener_subitem_por_id($conexion, $id_subitem);
      $item = RepositorioItem::obtener_item_por_id($conexion, $subitem->obtener_id_item());
      $id_rfq = $item->obtener_id_rfq();

      // Create audit trail
      AuditTrailRepository::create_audit_trail_subitem_created(
        $conexion,
        $id_subitem,
        'Provider',
        $provider,
        'Provider',
        $id_rfq
      );

      // Close the database connection
      Conexion::cerrar_conexion();

      if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
      } else {
        Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#caja_items');
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
