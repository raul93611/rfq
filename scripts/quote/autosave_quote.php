<?php
if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  exit;
}
header('Content-Type: application/json');

$id_rfq              = filter_input(INPUT_POST, 'id_rfq',              FILTER_VALIDATE_INT);
$taxes               = filter_input(INPUT_POST, 'taxes',               FILTER_VALIDATE_FLOAT);
$profit              = filter_input(INPUT_POST, 'profit',              FILTER_VALIDATE_FLOAT);
$total_cost          = filter_input(INPUT_POST, 'total_cost',          FILTER_VALIDATE_FLOAT);
$total_price         = filter_input(INPUT_POST, 'total_price',         FILTER_VALIDATE_FLOAT);
$additional_general  = filter_input(INPUT_POST, 'additional_general',  FILTER_SANITIZE_SPECIAL_CHARS);
$payment_terms       = filter_input(INPUT_POST, 'payment_terms',       FILTER_SANITIZE_SPECIAL_CHARS);
$shipping            = trim($_POST['shipping'] ?? '');
$shipping_cost       = filter_input(INPUT_POST, 'shipping_cost',       FILTER_VALIDATE_FLOAT);
$services_payment_term = filter_input(INPUT_POST, 'services_payment_term', FILTER_SANITIZE_SPECIAL_CHARS);

$id_items                   = filter_input(INPUT_POST, 'id_items',                   FILTER_SANITIZE_SPECIAL_CHARS);
$id_subitems                = filter_input(INPUT_POST, 'id_subitems',                FILTER_SANITIZE_SPECIAL_CHARS);
$partes_total_price         = filter_input(INPUT_POST, 'partes_total_price',         FILTER_SANITIZE_SPECIAL_CHARS);
$partes_total_price_subitems = filter_input(INPUT_POST, 'partes_total_price_subitems', FILTER_SANITIZE_SPECIAL_CHARS);
$unit_prices                = filter_input(INPUT_POST, 'unit_prices',                FILTER_SANITIZE_SPECIAL_CHARS);
$unit_prices_subitems       = filter_input(INPUT_POST, 'unit_prices_subitems',       FILTER_SANITIZE_SPECIAL_CHARS);
$additional                 = filter_input(INPUT_POST, 'additional',                 FILTER_SANITIZE_SPECIAL_CHARS);
$additional_subitems        = filter_input(INPUT_POST, 'additional_subitems',        FILTER_SANITIZE_SPECIAL_CHARS);

if (!$id_rfq) {
  echo json_encode(['success' => false, 'message' => 'Invalid RFQ ID']);
  exit;
}

try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  RepositorioRfq::update_variables(
    $conexion,
    $payment_terms,
    $taxes   ?? 0,
    $profit  ?? 0,
    $total_cost  ?? 0,
    $total_price ?? 0,
    $additional_general,
    htmlspecialchars($shipping),
    $shipping_cost ?? 0,
    $id_rfq
  );

  RepositorioRfq::insert_calc(
    $conexion,
    $id_items                    ?? '',
    $id_subitems                 ?? '',
    $partes_total_price          ?? '',
    $partes_total_price_subitems ?? '',
    $unit_prices                 ?? '',
    $unit_prices_subitems        ?? '',
    $additional                  ?? '',
    $additional_subitems         ?? ''
  );

  if ($services_payment_term) {
    ServiceRepository::calc_items_with_CC($conexion, $services_payment_term, $id_rfq);
  }

  AuditTrailRepository::items_table_events(
    $conexion,
    $taxes   ?? 0,
    $_POST['taxes_original']              ?? '',
    $profit  ?? 0,
    $_POST['profit_original']             ?? '',
    $additional_general,
    $_POST['additional_general_original'] ?? '',
    $payment_terms,
    $_POST['payment_terms_original']      ?? '',
    $shipping,
    $_POST['shipping_original']           ?? '',
    $shipping_cost ?? 0,
    $_POST['shipping_cost_original']      ?? '',
    $id_rfq
  );

  Conexion::cerrar_conexion();
  echo json_encode(['success' => true]);
} catch (Exception $e) {
  if (isset($conexion)) Conexion::cerrar_conexion();
  echo json_encode(['success' => false, 'message' => htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')]);
}
