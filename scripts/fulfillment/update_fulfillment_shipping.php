<?php
header('Content-Type: application/json');

try {
  if (!isset($_POST['shipping_counter'], $_POST['id_rfq'])) {
    throw new Exception('Missing required POST parameters.');
  }

  $shipping_counter = intval($_POST['shipping_counter']);
  $id_rfq = $_POST['id_rfq'];

  $shippings = [];
  $shippings_original = [];
  $amounts = [];
  $amounts_original = [];

  for ($i = 0; $i <= $shipping_counter; $i++) {
    if (!isset($_POST['fulfillment_shipping' . $i], $_POST['fulfillment_shipping_original' . $i], $_POST['amount' . $i], $_POST['amount_original' . $i])) {
      throw new Exception('Missing required POST parameters for index ' . $i);
    }

    $shippings[] = $_POST['fulfillment_shipping' . $i];
    $shippings_original[] = $_POST['fulfillment_shipping_original' . $i];
    $amounts[] = $_POST['amount' . $i];
    $amounts_original[] = $_POST['amount_original' . $i];
  }

  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  RepositorioRfq::update_fulfillment_shipping($conexion, implode('|', $shippings), implode('|', $amounts), $id_rfq);
  RepositorioRfq::set_fulfillment_profit_and_total($conexion, $id_rfq);
  FulfillmentAuditTrailRepository::shipping_event($conexion, $shippings, $shippings_original, $amounts, $amounts_original, $id_rfq);

  Conexion::cerrar_conexion();

  $response = [
    'id_rfq' => $id_rfq,
  ];
} catch (Exception $e) {
  $response = [
    'error' => 'ERROR: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'),
  ];

  if (Conexion::obtener_conexion()) {
    Conexion::cerrar_conexion();
  }
}

echo json_encode($response);
