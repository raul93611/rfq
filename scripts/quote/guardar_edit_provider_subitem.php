<?php
if (isset($_POST['guardar_cambios_provider_subitem'])) {
  // Sanitize and validate inputs
  $id_provider_subitem = filter_input(INPUT_POST, 'id_provider_subitem', FILTER_VALIDATE_INT);
  $id_rfq = filter_input(INPUT_POST, 'id_rfq', FILTER_VALIDATE_INT);
  $provider = filter_input(INPUT_POST, 'provider', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

  // Original values for audit trail (not sanitized as they are for comparison)
  $provider_original = $_POST['provider_original'];
  $price_original = $_POST['price_original'];

  // Validate required inputs
  if ($id_provider_subitem && $id_rfq && $provider && $price !== false) {
    try {
      // Open database connection
      Conexion::abrir_conexion();
      $conexion = Conexion::obtener_conexion();

      // Update provider subitem
      RepositorioProviderSubitem::actualizar_provider_subitem(
        $conexion,
        $id_provider_subitem,
        $provider,
        $price
      );

      // Retrieve updated provider subitem and associated subitem
      $provider_subitem = RepositorioProviderSubitem::obtener_provider_subitem_por_id($conexion, $id_provider_subitem);
      $subitem = RepositorioSubitem::obtener_subitem_por_id($conexion, $provider_subitem->obtener_id_subitem());

      // Create audit trail
      AuditTrailRepository::edit_provider_subitem_events(
        $conexion,
        $provider,
        $provider_original,
        $price,
        $price_original,
        $subitem->obtener_id(),
        $id_rfq
      );

      // Close the database connection
      Conexion::cerrar_conexion();

      // Redirect to the updated page
      Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#subitem' . $subitem->obtener_id());
    } catch (Exception $e) {
      // Handle exceptions and close the connection if open
      if (isset($conexion)) {
        Conexion::cerrar_conexion();
      }
      echo 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
  } else {
    echo 'Error: Missing or invalid required fields.';
  }
}
