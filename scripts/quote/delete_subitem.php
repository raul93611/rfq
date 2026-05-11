<?php
try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  $subitem = RepositorioSubitem::obtener_subitem_por_id($conexion, $id_subitem);
  $item = RepositorioItem::obtener_item_por_id($conexion, $subitem->obtener_id_item());
  $id_rfq = $item->obtener_id_rfq();

  RepositorioSubitem::delete_subitem($conexion, $id_subitem);

  AuditTrailRepository::create_audit_trail_item_deleted(
    $conexion,
    'Subitem',
    $subitem->obtener_part_number_project(),
    'Part Number',
    $id_rfq
  );

  Conexion::cerrar_conexion();

  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'id_rfq' => $id_rfq]);
  } else {
    Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq . '#caja_items');
  }
} catch (Exception $e) {
  if (isset($conexion)) { Conexion::cerrar_conexion(); }
  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
  } else {
    echo 'Error: ' . $e->getMessage();
  }
  exit;
}
