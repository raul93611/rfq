<?php
/**
 * JSON: full Bid Pipeline Metrics payload for a period.
 * Params (GET): mode=year|quarter|month, year, quarter(1-4), month(1-12).
 */
if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

header('Content-Type: application/json');

$mode = $_GET['mode'] ?? 'year';
$mode = in_array($mode, ['year', 'quarter', 'month'], true) ? $mode : 'year';

$period = ['mode' => $mode, 'year' => (int)($_GET['year'] ?? date('Y'))];
if ($mode === 'quarter') {
  $period['quarter'] = max(1, min(4, (int)($_GET['quarter'] ?? 1)));
}
if ($mode === 'month') {
  $period['month'] = max(1, min(12, (int)($_GET['month'] ?? date('n'))));
}

try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();
  $data = PipelineMetricsRepository::getMetrics($conexion, $period);
  Conexion::cerrar_conexion();
  echo json_encode($data);
} catch (Exception $e) {
  http_response_code(500);
  error_log('Pipeline metrics error: ' . $e->getMessage());
  echo json_encode(['error' => 'Failed to load metrics']);
}
