<?php
/**
 * JSON: drill-down quote list for a clicked chart segment.
 * Params (GET): period (mode/year/quarter/month) + a bucket spec:
 *   type=status   key=<status key>
 *   type=category bucket=awards|submitted  category=<type_of_bid>
 *   type=priced   key=submitted|not_submitted|no_bid
 *   type=winloss  key=won|lost|pending
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
if ($mode === 'quarter') $period['quarter'] = max(1, min(4, (int)($_GET['quarter'] ?? 1)));
if ($mode === 'month')   $period['month']   = max(1, min(12, (int)($_GET['month'] ?? date('n'))));

$spec = ['type' => $_GET['type'] ?? ''];
foreach (['key', 'bucket', 'category'] as $k) {
  if (isset($_GET[$k])) $spec[$k] = $_GET[$k];
}

try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();
  $rows = PipelineMetricsRepository::getDrillDown($conexion, $period, $spec);
  Conexion::cerrar_conexion();

  // Attach the edit-page URL per quote so the front-end stays route-agnostic.
  $editBase = EDITAR_COTIZACION . '/';
  foreach ($rows as &$r) {
    $r['url'] = $editBase . $r['id'];
  }
  unset($r);

  echo json_encode(['quotes' => $rows]);
} catch (Exception $e) {
  http_response_code(500);
  error_log('Pipeline metrics drill-down error: ' . $e->getMessage());
  echo json_encode(['error' => 'Failed to load quotes']);
}
