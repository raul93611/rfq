<?php
if (isset($_POST['guardar_subitem'])) {
  // Sanitize and validate inputs
  $id_item = filter_input(INPUT_POST, 'id_item', FILTER_VALIDATE_INT);
  $brand = filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $brand_project = filter_input(INPUT_POST, 'brand_project', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $part_number = filter_input(INPUT_POST, 'part_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $part_number_project = filter_input(INPUT_POST, 'part_number_project', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $description_project = filter_input(INPUT_POST, 'description_project', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
  $comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $website = filter_input(INPUT_POST, 'website', FILTER_SANITIZE_URL);

  // Validate required inputs
  if ($id_item && $brand && $part_number && $description && $quantity !== false) {
    try {
      // Open database connection
      Conexion::abrir_conexion();
      $conexion = Conexion::obtener_conexion();

      // Create subitem
      $subitem = new Subitem(
        '',
        $id_item,
        0,
        $brand,
        $brand_project,
        $part_number,
        $part_number_project,
        $description,
        $description_project,
        $quantity,
        0,
        0,
        $comments,
        $website,
        '',
        null
      );
      $id = RepositorioSubitem::insertar_subitem($conexion, $subitem);

      // Retrieve item information
      $item = RepositorioItem::obtener_item_por_id($conexion, $id_item);
      $id_rfq = $item->obtener_id_rfq();

      // Create audit trail
      AuditTrailRepository::create_audit_trail_subitem_created(
        $conexion,
        $id,
        'Subitem',
        $part_number_project,
        'Part Number',
        $id_rfq
      );

      // Close the database connection
      Conexion::cerrar_conexion();

      // Redirect to the updated page
      Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#subitem' . $id);
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
