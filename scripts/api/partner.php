<?php
/**
 * Partner-facing API entry (token auth, no session). Routing + auth + payloads live in
 * app/Partner/PartnerApi.inc.php; this only feeds it the request and emits the result.
 * $partner_segments is set by index.php (path after "api/").
 */
session_write_close(); // no session here — don't hold the lock

$headers = [];
foreach ($_SERVER as $k => $v) {
  if (strpos($k, 'HTTP_') === 0) $headers[strtolower(str_replace('_', '-', substr($k, 5)))] = $v;
}
// Some Apache/CGI setups drop Authorization from $_SERVER.
if (!isset($headers['authorization']) && function_exists('getallheaders')) {
  foreach (getallheaders() as $k => $v) $headers[strtolower($k)] = $v;
}

Conexion::abrir_conexion();
try {
  $result = PartnerApi::handle(
    Conexion::obtener_conexion(), $_SERVER['REQUEST_METHOD'] ?? 'GET', $partner_segments ?? [], $_GET, $headers,
    file_get_contents('php://input')
  );
} catch (Throwable $e) {
  error_log('Partner API error: ' . $e->getMessage());
  $result = ['status' => 500, 'json' => ['error' => 'server_error'], 'file' => null, 'name' => null];
}
Conexion::cerrar_conexion();

http_response_code($result['status']);
header('Cache-Control: no-store');
if ($result['file']) {
  header('Content-Type: application/octet-stream');
  header('Content-Length: ' . filesize($result['file']));
  header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($result['name']));
  readfile($result['file']);
} elseif ($result['json'] !== null) {
  header('Content-Type: application/json');
  echo json_encode($result['json']);
}
