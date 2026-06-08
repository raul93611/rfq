<?php
/**
 * Integration test for RepositorioRfq::getAnnualAwardsDataByMonthForYears.
 *
 * Drives the Charts-tab "Annual Awards" 3-year comparison. Inserts awarded
 * quotes (with services) in far-future sentinel years, asserts the per-year /
 * per-month buckets and that a quote's value includes its services subtotal,
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

/** Insert one awarded rfq on a given fecha_award (YYYY-MM-DD). Returns the new id. */
function insertAwardQuote($conexion, $fecha_award, array $o = []) {
  $f = array_merge([
    'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'AATEST',
    'email_code' => 'AATEST-' . uniqid(), 'type_of_bid' => 'IT',
    'issue_date' => '01/01/2000', 'end_date' => '01/01/2000', 'status' => 1, 'completado' => 1,
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
  // 2095 · March: two awards (100+services 50, and 200) -> month idx 2 = {2, 350}
  $a1 = insertAwardQuote($conexion, '2095-03-15', ['total_price' => 100]);
  insertSvc($conexion, $a1, 50);
  insertAwardQuote($conexion, '2095-03-20', ['total_price' => 200]);
  // 2096 · July: one award (300, no services) -> month idx 6 = {1, 300}
  insertAwardQuote($conexion, '2096-07-10', ['total_price' => 300]);
  // Noise that must be excluded: a non-award and a deleted award in 2095
  insertAwardQuote($conexion, '2095-05-01', ['award' => 0, 'total_price' => 999]);
  insertAwardQuote($conexion, '2095-05-01', ['deleted' => 1, 'total_price' => 999]);

  $data = RepositorioRfq::getAnnualAwardsDataByMonthForYears($conexion, [2094, 2095, 2096]);

  echo "[Shape]\n";
  check('returns a bucket per requested year', [2094, 2095, 2096], array_keys($data));
  check('each year has 12 month entries', [12, 12, 12],
    [count($data[2094]), count($data[2095]), count($data[2096])]);
  check('a non-requested year is absent', false, isset($data[2093]));

  echo "[Empty year]\n";
  check('2094 annual count = 0', 0, array_sum(array_column($data[2094], 'total_quotes')));
  check('2094 annual value = 0', 0.0, (float)array_sum(array_column($data[2094], 'total_price')));

  echo "[2095 — two March awards, services included]\n";
  check('2095 March count = 2', 2, $data[2095][2]['total_quotes']);
  check('2095 March value = 350 (100+50 services +200)', 350.0, (float)$data[2095][2]['total_price']);
  check('2095 annual count = 2', 2, array_sum(array_column($data[2095], 'total_quotes')));
  check('2095 annual value = 350', 350.0, (float)array_sum(array_column($data[2095], 'total_price')));
  check('2095 a non-award month is zero', 0, $data[2095][4]['total_quotes']); // May (idx 4) excluded

  echo "[2096 — one July award]\n";
  check('2096 July count = 1', 1, $data[2096][6]['total_quotes']);
  check('2096 July value = 300', 300.0, (float)$data[2096][6]['total_price']);

} finally {
  $conexion->rollBack();
}

echo "\n==== $pass passed, $fail failed ====\n";
exit($fail === 0 ? 0 : 1);
