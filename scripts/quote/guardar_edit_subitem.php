<?php
if (isset($_POST['guardar_cambios_subitem'])) {
  // Sanitize and validate inputs
  $id_subitem = filter_input(INPUT_POST, 'id_subitem', FILTER_VALIDATE_INT);
  $id_rfq = filter_input(INPUT_POST, 'id_rfq', FILTER_VALIDATE_INT);
  $brand = filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $brand_project = filter_input(INPUT_POST, 'brand_project', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $part_number = filter_input(INPUT_POST, 'part_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $part_number_project = filter_input(INPUT_POST, 'part_number_project', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $description_project = filter_input(INPUT_POST, 'description_project', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
  $comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $website = filter_input(INPUT_POST, 'website', FILTER_SANITIZE_URL);

  // Original values for audit trail (not sanitized as they are for comparison)
  $brand_original = $_POST['brand_original'];
  $brand_project_original = $_POST['brand_project_original'];
  $part_number_original = $_POST['part_number_original'];
  $part_number_project_original = $_POST['part_number_project_original'];
  $description_original = $_POST['description_original'];
  $description_project_original = $_POST['description_project_original'];
  $quantity_original = $_POST['quantity_original'];
  $comments_original = $_POST['comments_original'];
  $website_original = $_POST['website_original'];

  // Validate required inputs
  if ($id_subitem && $id_rfq && $brand && $brand_project && $part_number && $part_number_project && $description && $description_project && $quantity !== false) {
    try {
      // Open database connection
      Conexion::abrir_conexion();
      $conexion = Conexion::obtener_conexion();

      // Update subitem
      RepositorioSubitem::actualizar_subitem($conexion, $id_subitem, $brand, $brand_project, $part_number, $part_number_project, $description, $description_project, $quantity, $comments, $website);

      // Create audit trail
      AuditTrailRepository::edit_subitem_events(
        $conexion,
        $brand,
        $brand_original,
        $brand_project,
        $brand_project_original,
        $part_number,
        $part_number_original,
        $part_number_project,
        $part_number_project_original,
        $description,
        $description_original,
        $description_project,
        $description_project_original,
        $quantity,
        $quantity_original,
        $comments,
        $comments_original,
        $website,
        $website_original,
        $id_subitem,
        $id_rfq
      );

      // Close the database connection
      Conexion::cerrar_conexion();

      // Redirect to the updated page
      Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#subitem' . $id_subitem);
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
