<?php
if (isset($_POST['guardar_item'])) {
  // Sanitize and validate inputs
  $id_rfq = filter_input(INPUT_POST, 'id_rfq', FILTER_VALIDATE_INT);
  $brand = filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $brand_project = filter_input(INPUT_POST, 'brand_project', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $part_number = filter_input(INPUT_POST, 'part_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $part_number_project = filter_input(INPUT_POST, 'part_number_project', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
  $description_project = htmlspecialchars($_POST['description_project'], ENT_QUOTES, 'UTF-8');
  $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
  $comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $website = filter_input(INPUT_POST, 'website', FILTER_SANITIZE_URL);

  // Validate required inputs
  if ($id_rfq && $brand && $part_number && $description && $quantity) {
    try {
      // Open database connection
      Conexion::abrir_conexion();
      $conexion = Conexion::obtener_conexion();

      // Create item
      $item = new Item(
        '',
        $id_rfq,
        $_SESSION['user']->obtener_id(),
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

      // Insert item and create audit trail
      $id = RepositorioItem::insertar_item($conexion, $item);
      AuditTrailRepository::create_audit_trail_item_created(
        $conexion,
        $id,
        'Item',
        $part_number_project,
        'Part Number',
        $id_rfq
      );

      // Close the database connection
      Conexion::cerrar_conexion();

      // Redirect if item was inserted
      if ($id) {
        Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#item' . $id);
      } else {
        throw new Exception('Failed to insert item.');
      }
    } catch (Exception $e) {
      // Handle exceptions and close the connection if open
      if (isset($conexion)) {
        Conexion::cerrar_conexion();
      }
      echo 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
  } else {
    echo 'Error: Missing required fields.';
  }
}
