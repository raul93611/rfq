<?php
/**
 * Integration test for RepositorioRfq::getAnnualAwardsDataByMonthForYears.
 *
 * Drives the Charts-tab "Annual Awards" 3-year comparison. The cohort matches the
 * Bid Pipeline Metrics dashboard:
 *   - year/month basis = issue_date (NOT fecha_award — the business tracks no award date)
 *   - "awarded" = award OR fulfillment OR invoice OR submitted_invoice
 *   - value = product total + services subtotal
 *
 * Inserts quotes in far-future sentinel years, asserts the per-year/per-month buckets,
 * then ROLLS BACK so nothing persists.
 *
 * Run:  docker exec lamp-php83 php /var/www/html/rfq/tests/php/annual_awards_test.php
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

$conexion = Conexion::obtener_conexion();

/**
 * Insert one rfq with a given issue_date (MM/DD/YYYY). Awarded flags default OFF and
 * fecha_award stays NULL — proving the chart buckets on issue_date, not award date.
 */
function insertQuote($conexion, $issue_date, array $o = []) {
  $f = array_merge([
    'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'AATEST',
    'email_code' => 'AATEST-' . uniqid(), 'type_of_bid' => 'IT',
    'issue_date' => $issue_date, 'end_date' => $issue_date, 'status' => 1, 'completado' => 1,
    'comments' => 'No comments', 'award' => 0, 'payment_terms' => 'Net 30',
    'address' => '', 'ship_to' => '', 'ship_via' => '', 'taxes' => 0, 'profit' => 0,
    'additional' => '', 'shipping_cost' => 0, 'shipping' => '', 'fullfillment' => 0,
    'contract_number' => '', 'sources_sought' => 0, 'total_price' => 0,
    'invoice' => 0, 'submitted_invoice' => 0, 'deleted' => 0, 'name' => 'Test Opportunity',
  ], $o);
  $cols = array_keys($f);
  $ph = array_map(fn($c) => ':' . $c, $cols);
  $sql = 'INSERT INTO rfq (' . implode(',', $cols) . ') VALUES (' . implode(',', $ph) . ')';
  $stmt = $conexion->prepare($sql);
  foreach ($f as $c => $v) { $stmt->bindValue(':' . $c, $v); }
  $stmt->execute();
  return (int)$conexion->lastInsertId();
}

/** Insert one services line item for a quote. */
function insertSvc($conexion, $id_rfq, $total_price) {
  $sql = 'INSERT INTO services (id_rfq, description, quantity, unit_price, total_price)
          VALUES (:id_rfq, :description, :quantity, :unit_price, :total_price)';
  $stmt = $conexion->prepare($sql);
  $stmt->bindValue(':id_rfq', $id_rfq, PDO::PARAM_INT);
  $stmt->bindValue(':description', 'AATEST service');
  $stmt->bindValue(':quantity', 1);
  $stmt->bindValue(':unit_price', $total_price);
  $stmt->bindValue(':total_price', $total_price);
  $stmt->execute();
}

$conexion->beginTransaction();
try {
  // 2095 · March: award=1 (100 + services 50) and fulfillment-only (200) -> idx 2 = {2, 350}
  $a1 = insertQuote($conexion, '03/15/2095', ['award' => 1, 'total_price' => 100]);
  insertSvc($conexion, $a1, 50);
  insertQuote($conexion, '03/20/2095', ['fullfillment' => 1, 'total_price' => 200]); // award flag OFF
  // 2095 · July: invoice-only (300) -> idx 6 = {1, 300}
  insertQuote($conexion, '07/10/2095', ['invoice' => 1, 'total_price' => 300]);
  // 2095 · August: submitted_invoice-only (25) -> idx 7 = {1, 25}
  insertQuote($conexion, '08/01/2095', ['submitted_invoice' => 1, 'total_price' => 25]);
  // 2095 · May noise: a non-award and a deleted award -> excluded
  insertQuote($conexion, '05/01/2095', ['total_price' => 999]);                        // no award flags
  insertQuote($conexion, '05/02/2095', ['award' => 1, 'deleted' => 1, 'total_price' => 999]);
  // 2096 · Feb: award=1 (400) -> idx 1 = {1, 400}
  insertQuote($conexion, '02/02/2096', ['award' => 1, 'total_price' => 400]);

  $data = RepositorioRfq::getAnnualAwardsDataByMonthForYears($conexion, [2094, 2095, 2096]);

  echo "[Shape]\n";
  check('returns a bucket per requested year', [2094, 2095, 2096], array_keys($data));
  check('each year has 12 month entries', [12, 12, 12],
    [count($data[2094]), count($data[2095]), count($data[2096])]);
  check('a non-requested year is absent', false, isset($data[2093]));

  echo "[Empty year]\n";
  check('2094 annual count = 0', 0, array_sum(array_column($data[2094], 'total_quotes')));
  check('2094 annual value = 0', 0.0, (float)array_sum(array_column($data[2094], 'total_price')));

  echo "[2095 — issue-month buckets, broad award def, services in value]\n";
  check('2095 March count = 2 (award + fulfillment-only)', 2, $data[2095][2]['total_quotes']);
  check('2095 March value = 350 (100+50 services +200)', 350.0, (float)$data[2095][2]['total_price']);
  check('2095 July count = 1 (invoice-only)', 1, $data[2095][6]['total_quotes']);
  check('2095 July value = 300', 300.0, (float)$data[2095][6]['total_price']);
  check('2095 Aug count = 1 (submitted_invoice-only)', 1, $data[2095][7]['total_quotes']);
  check('2095 May = 0 (non-award + deleted excluded)', 0, $data[2095][4]['total_quotes']);
  check('2095 annual count = 4', 4, array_sum(array_column($data[2095], 'total_quotes')));
  check('2095 annual value = 675', 675.0, (float)array_sum(array_column($data[2095], 'total_price')));

  echo "[2096]\n";
  check('2096 Feb count = 1', 1, $data[2096][1]['total_quotes']);
  check('2096 Feb value = 400', 400.0, (float)$data[2096][1]['total_price']);
  check('2096 annual count = 1', 1, array_sum(array_column($data[2096], 'total_quotes')));

} finally {
  $conexion->rollBack();
}

echo "\n==== $pass passed, $fail failed ====\n";
exit($fail === 0 ? 0 : 1);
