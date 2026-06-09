<?php
/**
 * Integration test for RepositorioRfq::getAnnualAwardsDataByMonthForYears.
 *
 * Drives the Charts-tab "Annual Awards" 3-year comparison. Per leadership this counts
 * awards by AWARD DATE:
 *   - year/month basis = fecha_award (NOT issue_date)
 *   - "awarded" = award = 1
 *   - value = product total + services subtotal
 *
 * Inserts awarded quotes (with services) in far-future sentinel years, asserts the
 * per-year/per-month buckets, then ROLLS BACK so nothing persists.
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
 * Insert one rfq. fecha_award (YYYY-MM-DD or null) drives the chart; issue_date is set to
 * a DIFFERENT year on purpose to prove the chart buckets on the award date, not issue date.
 */
function insertQuote($conexion, $fecha_award, array $o = []) {
  $f = array_merge([
    'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'AATEST',
    'email_code' => 'AATEST-' . uniqid(), 'type_of_bid' => 'IT',
    'issue_date' => '01/01/1999', 'end_date' => '01/01/1999', 'status' => 1, 'completado' => 1,
    'comments' => 'No comments', 'award' => 1, 'fecha_award' => $fecha_award, 'payment_terms' => 'Net 30',
    'address' => '', 'ship_to' => '', 'ship_via' => '', 'taxes' => 0, 'profit' => 0,
    'additional' => '', 'shipping_cost' => 0, 'shipping' => '', 'fullfillment' => 0,
    'contract_number' => '', 'sources_sought' => 0, 'total_price' => 0,
    'invoice' => 0, 'submitted_invoice' => 0, 'deleted' => 0, 'name' => 'Test Award',
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
  // 2095 · March: two awards (100 + services 50, and 200) -> month idx 2 = {2, 350}
  $a1 = insertQuote($conexion, '2095-03-15', ['total_price' => 100]);
  insertSvc($conexion, $a1, 50);
  insertQuote($conexion, '2095-03-20', ['total_price' => 200]);
  // 2096 · July: one award (300) -> month idx 6 = {1, 300}
  insertQuote($conexion, '2096-07-10', ['total_price' => 300]);
  // Excluded: a non-award, a deleted award, and an awarded bid with NO award date
  insertQuote($conexion, '2095-05-01', ['award' => 0, 'total_price' => 999]);
  insertQuote($conexion, '2095-05-01', ['deleted' => 1, 'total_price' => 999]);
  insertQuote($conexion, null,         ['total_price' => 999]); // award=1 but fecha_award NULL

  $data = RepositorioRfq::getAnnualAwardsDataByMonthForYears($conexion, [2094, 2095, 2096]);

  echo "[Shape]\n";
  check('returns a bucket per requested year', [2094, 2095, 2096], array_keys($data));
  check('each year has 12 month entries', [12, 12, 12],
    [count($data[2094]), count($data[2095]), count($data[2096])]);
  check('a non-requested year is absent', false, isset($data[2093]));

  echo "[Empty year]\n";
  check('2094 annual count = 0', 0, array_sum(array_column($data[2094], 'total_quotes')));
  check('2094 annual value = 0', 0.0, (float)array_sum(array_column($data[2094], 'total_price')));

  echo "[2095 — two March awards by award date, services in value]\n";
  check('2095 March count = 2', 2, $data[2095][2]['total_quotes']);
  check('2095 March value = 350 (100+50 services +200)', 350.0, (float)$data[2095][2]['total_price']);
  check('2095 annual count = 2 (non-award, deleted, NULL-date excluded)', 2,
    array_sum(array_column($data[2095], 'total_quotes')));
  check('2095 May = 0 (non-award + deleted)', 0, $data[2095][4]['total_quotes']);

  echo "[2096]\n";
  check('2096 July count = 1', 1, $data[2096][6]['total_quotes']);
  check('2096 July value = 300', 300.0, (float)$data[2096][6]['total_price']);
  check('2096 annual count = 1', 1, array_sum(array_column($data[2096], 'total_quotes')));

} finally {
  $conexion->rollBack();
}

echo "\n==== $pass passed, $fail failed ====\n";
exit($fail === 0 ? 0 : 1);
