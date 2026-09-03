<?php
/**
 * Integration test for the "Remove Submitted Invoice status" bug fix.
 *
 * Submitted Invoice is being eliminated as a distinct status: Invoice becomes
 * terminal, and any quote previously flagged submitted_invoice=1 must show and
 * list as Invoice. The DB column, Rfq model getter, and existing audit_trails
 * rows are explicitly left alone (out of scope) — only the query-level
 * exclusion and the dedicated Submitted Invoice list repository methods go.
 *
 * Transaction-isolated (ROLLBACK), same pattern as pipeline_table_test.php.
 * Run:  docker exec lamp-php83 php /var/www/html/rfq/tests/php/remove_submitted_invoice_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Quote/Rfq.inc.php';
require_once $root . 'app/Quote/RepositorioRfq.inc.php';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = $expected === $actual;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

function insertQuote($conexion, array $o = []) {
  $f = array_merge([
    'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'RSITEST',
    'email_code' => 'RSI-' . uniqid(), 'type_of_bid' => 'IT',
    'issue_date' => '01/01/1999', 'end_date' => '01/01/1999', 'status' => 1, 'completado' => 1,
    'comments' => 'No comments', 'award' => 1, 'payment_terms' => 'Net 30',
    'address' => '', 'ship_to' => '', 'ship_via' => '', 'taxes' => 0, 'profit' => 0,
    'additional' => '', 'shipping_cost' => 0, 'shipping' => '', 'fullfillment' => 1,
    'contract_number' => '', 'total_price' => 0,
    'invoice' => 1, 'submitted_invoice' => 0, 'deleted' => 0, 'name' => 'Remove Submitted Invoice Test',
  ], $o);
  $cols = array_keys($f);
  $ph = array_map(fn($c) => ':' . $c, $cols);
  $sql = 'INSERT INTO rfq (' . implode(',', $cols) . ') VALUES (' . implode(',', $ph) . ')';
  $stmt = $conexion->prepare($sql);
  foreach ($f as $c => $v) { $stmt->bindValue(':' . $c, $v); }
  $stmt->execute();
  return (int)$conexion->lastInsertId();
}

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$conexion->beginTransaction();

try {
  echo "[legacy submitted_invoice=1 quote now counts/lists as Invoice]\n";
  $marker = 'RSI-' . uniqid();
  $idLegacy = insertQuote($conexion, ['submitted_invoice' => 1, 'email_code' => $marker]);

  $beforeCount = RepositorioRfq::getTotalInvoiceQuotesCount($conexion);
  check('a legacy submitted_invoice=1 quote is now counted in the Invoice list', true, $beforeCount >= 1);

  $rows = RepositorioRfq::getInvoiceQuotes($conexion, 0, 50, $marker, 0, 'DESC');
  $ids = array_map(fn($r) => (int)$r['id'], $rows);
  check('legacy submitted_invoice=1 quote appears in getInvoiceQuotes()', true, in_array($idLegacy, $ids, true));

  $filteredCount = RepositorioRfq::getTotalFilteredInvoiceQuotesCount($conexion, $marker);
  check('getTotalFilteredInvoiceQuotesCount finds it too', 1, (int)$filteredCount);

  echo "[a normal invoice=1, submitted_invoice=0 quote is unaffected]\n";
  $marker2 = 'RSI-' . uniqid();
  $idNormal = insertQuote($conexion, ['submitted_invoice' => 0, 'email_code' => $marker2]);
  $rows2 = RepositorioRfq::getInvoiceQuotes($conexion, 0, 50, $marker2, 0, 'DESC');
  $ids2 = array_map(fn($r) => (int)$r['id'], $rows2);
  check('ordinary invoice quote still appears in getInvoiceQuotes()', true, in_array($idNormal, $ids2, true));

  echo "[Submitted Invoice repository surface fully removed]\n";
  check('getSubmittedInvoiceQuotes removed', false, method_exists('RepositorioRfq', 'getSubmittedInvoiceQuotes'));
  check('getTotalSubmittedInvoiceQuotesCount removed', false, method_exists('RepositorioRfq', 'getTotalSubmittedInvoiceQuotesCount'));
  check('getTotalFilteredSubmittedInvoiceQuotesCount removed', false, method_exists('RepositorioRfq', 'getTotalFilteredSubmittedInvoiceQuotesCount'));
  check('check_submitted_invoice_and_date removed', false, method_exists('RepositorioRfq', 'check_submitted_invoice_and_date'));
  check('remove_submitted_invoice removed', false, method_exists('RepositorioRfq', 'remove_submitted_invoice'));

  echo "[left alone on purpose: column + model getter still work]\n";
  $q = RepositorioRfq::obtener_cotizacion_por_id($conexion, $idLegacy);
  check('Rfq::obtener_submitted_invoice() getter still reflects the raw column', 1, (int)$q->obtener_submitted_invoice());

} finally {
  $conexion->rollBack();
  Conexion::cerrar_conexion();
}

echo "\n==== $pass passed, $fail failed ====\n";
exit($fail === 0 ? 0 : 1);
