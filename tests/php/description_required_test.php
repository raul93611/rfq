<?php
/**
 * Regression test for bugs/description-required-for-partner-sync.md.
 *
 * A blank Description used to save as rfq.name = NULL and the partner silently dropped the
 * opportunity. Covers:
 *   - ValidadorCotizacionRegistro: blank / whitespace Description fails, filled passes (New Quote)
 *   - save_information.php: blank Description is refused before anything is written (drawer)
 *   - PartnerSyncService::buildPayload(): a blank name falls back to the Solicitation #
 *
 * Read-only against the DB (the drawer guard returns before any write).
 * Run:  docker exec lamp-php84 php /var/www/html/rfq/tests/php/description_required_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Quote/Rfq.inc.php';
require_once $root . 'app/Quote/RepositorioRfq.inc.php';
require_once $root . 'app/Quote/ValidadorCotizacion.inc.php';
require_once $root . 'app/Quote/ValidadorCotizacionRegistro.inc.php';
require_once $root . 'app/Partner/PartnerDocumentRepository.inc.php';
require_once $root . 'app/Partner/PartnerSyncService.inc.php';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  if ($expected === $actual) { $pass++; echo "  PASS  $label\n"; }
  else { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

Conexion::abrir_conexion();
$c = Conexion::obtener_conexion();

$newQuote = function ($name) use ($c) {
  return new ValidadorCotizacionRegistro(
    $c, 'DR-' . uniqid(), '01/01/2099', '01/02/2099 10:00', '01/01/2099', 'IT', 'RSantos', 'GSA-Buy', $name
  );
};

echo "[New Quote — Description required]\n";
foreach (['blank' => '', 'whitespace-only' => "  \n\t ", 'null' => null] as $label => $name) {
  $v = $newQuote($name);
  check("$label Description -> \"Must be fill out.\"", 'Must be fill out.', $v->obtener_error_name());
  check("$label Description -> registro_cotizacion_valida() false", false, $v->registro_cotizacion_valida());
}
$v = $newQuote('  Westpine AV Refresh ');
check('filled Description -> no error', '', $v->obtener_error_name());
check('filled Description -> registro_cotizacion_valida() true', true, $v->registro_cotizacion_valida());
ob_start(); $v->mostrar_error_name(); $html = ob_get_clean();
check('filled Description -> no error markup', '', $html);
ob_start(); $newQuote('')->mostrar_error_name(); $html = ob_get_clean();
check('blank Description -> error markup rendered', true, strpos($html, 'Must be fill out.') !== false);

echo "\n[Information drawer — blank Description refused before any write]\n";
$drawerSave = function ($name) use ($root) {
  $_POST = ['save_information' => '1', 'id_rfq' => '0', 'end_date' => '01/02/2099 10:00', 'internal_due_date' => '01/01/2099'];
  if ($name !== null) $_POST['name'] = $name;
  ob_start();
  try { include $root . 'scripts/quote/save_information.php'; }
  catch (Throwable $e) { echo 'THREW: ' . $e->getMessage(); }
  // header() warns "headers already sent" (this test has echoed); the JSON is the last line.
  $lines = preg_split('/\R/', trim(ob_get_clean()));
  return json_decode(end($lines), true);
};
foreach (['blank' => '', 'whitespace-only' => '   ', 'missing' => null] as $label => $name) {
  $res = $drawerSave($name);
  check("$label Description -> success false", false, $res['success'] ?? null);
  check("$label Description -> field 'name'", 'name', $res['field'] ?? null);
  check("$label Description -> message", 'Description must be filled out.', $res['message'] ?? null);
}

echo "\n[buildPayload — blank name falls back to the Solicitation #]\n";
$row = $c->query('SELECT * FROM rfq ORDER BY id LIMIT 1')->fetch(PDO::FETCH_ASSOC);
$row['solicitation_number'] = 'SOL-DR-1';
foreach (['NULL' => null, 'empty' => '', 'whitespace' => '   '] as $label => $name) {
  $row['name'] = $name;
  check("$label name -> Solicitation #", 'SOL-DR-1', PartnerSyncService::buildPayload($c, $row)['name']);
}
$row['name'] = '  Westpine AV Refresh ';
check('filled name -> trimmed name', 'Westpine AV Refresh', PartnerSyncService::buildPayload($c, $row)['name']);

Conexion::cerrar_conexion();

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
