<?php
if (isset($_POST['guardar_cambios_item'])) {
  // Sanitize and validate inputs
  $id_item = filter_input(INPUT_POST, 'id_item', FILTER_VALIDATE_INT);
  $id_rfq = filter_input(INPUT_POST, 'id_rfq', FILTER_VALIDATE_INT);
  $brand = filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $brand_project = filter_input(INPUT_POST, 'brand_project', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $part_number = filter_input(INPUT_POST, 'part_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $part_number_project = filter_input(INPUT_POST, 'part_number_project', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $description_project = filter_input(INPUT_POST, 'description_project', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
  $comments = trim($_POST["comments"]);
  $website = filter_input(INPUT_POST, 'website', FILTER_SANITIZE_URL);
  $id_room = empty($_POST['id_room']) ? null : htmlspecialchars($_POST['id_room']);

  // Validate required inputs
  if ($id_item && $id_rfq && $brand && $description && $quantity !== false) {
    try {
      // Open database connection
      Conexion::abrir_conexion();
      $conexion = Conexion::obtener_conexion();

      // Update item
      $item_editado = RepositorioItem::actualizar_item(
        $conexion,
        $id_item,
        $brand,
        $brand_project,
        $part_number,
        $part_number_project,
        $description,
        $description_project,
        $quantity,
        $comments,
        $website,
        $id_room
      );

      // Create audit trail
      AuditTrailRepository::edit_item_events(
        $conexion,
        $brand,
        $_POST['brand_original'],
        $brand_project,
        $_POST['brand_project_original'],
        $part_number,
        $_POST['part_number_original'],
        $part_number_project,
        $_POST['part_number_project_original'],
        $description,
        $_POST['description_original'],
        $description_project,
        $_POST['description_project_original'],
        $quantity,
        $_POST['quantity_original'],
        $comments,
        $_POST['comments_original'],
        $website,
        $_POST['website_original'],
        $id_item,
        $id_rfq
      );

      // Close the database connection
      Conexion::cerrar_conexion();

      if ($item_editado) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
          header('Content-Type: application/json');
          echo json_encode(['success' => true]);
        } else {
          Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#item' . $id_item);
        }
      } else {
        throw new Exception('Failed to update item.');
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
