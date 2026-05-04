<?php
header('Content-Type: application/json');

$id_rfq = intval($_POST['id_rfq'] ?? 0);
if (!$id_rfq) {
  echo json_encode([]);
  exit;
}

try {
  Conexion::abrir_conexion();
  $connection = Conexion::obtener_conexion();
  $entries = [];

  $sql1 = 'SELECT id, username, COALESCE(action_type, "field_modified") AS action_type,
            audit_trail, created_date, "quote" AS scope
            FROM audit_trails WHERE id_rfq = :id_rfq';
  $stmt = $connection->prepare($sql1);
  $stmt->bindValue(':id_rfq', $id_rfq, PDO::PARAM_INT);
  $stmt->execute();
  $entries = array_merge($entries, $stmt->fetchAll(PDO::FETCH_ASSOC));

  $sql2 = 'SELECT rat.id, rat.username, COALESCE(rat.action_type, "field_modified") AS action_type,
            rat.audit_trail, rat.created_date, "requote" AS scope
            FROM re_quote_audit_trails rat
            JOIN re_quotes rq ON rq.id = rat.id_re_quote
            WHERE rq.id_rfq = :id_rfq';
  $stmt = $connection->prepare($sql2);
  $stmt->bindValue(':id_rfq', $id_rfq, PDO::PARAM_INT);
  $stmt->execute();
  $entries = array_merge($entries, $stmt->fetchAll(PDO::FETCH_ASSOC));

  $sql3 = 'SELECT id, username, COALESCE(action_type, "field_modified") AS action_type,
            audit_trail, created_date, "fulfillment" AS scope
            FROM fulfillment_audit_trails WHERE id_rfq = :id_rfq';
  $stmt = $connection->prepare($sql3);
  $stmt->bindValue(':id_rfq', $id_rfq, PDO::PARAM_INT);
  $stmt->execute();
  $entries = array_merge($entries, $stmt->fetchAll(PDO::FETCH_ASSOC));

  Conexion::cerrar_conexion();

  usort($entries, function ($a, $b) {
    return strcmp($b['created_date'], $a['created_date']);
  });

  echo json_encode($entries);
} catch (Exception $e) {
  if (Conexion::obtener_conexion()) {
    Conexion::cerrar_conexion();
  }
  echo json_encode([]);
}
