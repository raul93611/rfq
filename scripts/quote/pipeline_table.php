<?php
/**
 * JSON: one page of the Bid Pipeline Metrics "Table" view.
 * Params (GET): period (mode=year|quarter|month|custom, year, quarter, month, from, to),
 *   page, and filters: quoteId, channel, emailCode, statuses[], bidType, user.
 * Each row carries everything the Quote Summary modal needs (name, value, docs, context, watched).
 */
if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

header('Content-Type: application/json');

$mode = $_GET['mode'] ?? 'year';
$mode = in_array($mode, ['year', 'quarter', 'month', 'custom'], true) ? $mode : 'year';

$period = ['mode' => $mode, 'year' => (int)($_GET['year'] ?? date('Y'))];
if ($mode === 'quarter') $period['quarter'] = max(1, min(4, (int)($_GET['quarter'] ?? 1)));
if ($mode === 'month')   $period['month']   = max(1, min(12, (int)($_GET['month'] ?? date('n'))));
if ($mode === 'custom') {
  $period['from'] = preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['from'] ?? '') ? $_GET['from'] : date('Y-01-01');
  $period['to']   = preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['to'] ?? '')   ? $_GET['to']   : date('Y-12-31');
}

$statuses = $_GET['statuses'] ?? [];
if (!is_array($statuses)) $statuses = [$statuses];

$filters = [
  'quoteId'   => trim((string)($_GET['quoteId'] ?? '')),
  'channel'   => trim((string)($_GET['channel'] ?? '')),
  'emailCode' => trim((string)($_GET['emailCode'] ?? '')),
  'statuses'  => $statuses,
  'bidType'   => trim((string)($_GET['bidType'] ?? '')),
  'user'      => trim((string)($_GET['user'] ?? '')),
];
$page = max(0, (int)($_GET['page'] ?? 0));

try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();
  $currentUserId = $_SESSION['user']->obtener_id();
  $result = PipelineTableRepository::getPage($conexion, $period, $filters, $currentUserId, $page);
  Conexion::cerrar_conexion();
  echo json_encode($result);
} catch (Exception $e) {
  http_response_code(500);
  error_log('Pipeline table error: ' . $e->getMessage());
  echo json_encode(['error' => 'Failed to load quotes']);
}
