<?php
/**
 * Integration test for PipelineMetricsRepository.
 *
 * Inserts a controlled set of quotes in a far-future sentinel year (so it never
 * collides with real data), runs the aggregations, asserts every bucket / KPI /
 * win-loss invariant, then ROLLS BACK so nothing persists.
 *
 * Run:  docker exec lamp-php83 php /var/www/html/rfq/tests/php/pipeline_metrics_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Quote/Rfq.inc.php';
require_once $root . 'app/Report/PipelineMetricsRepository.inc.php';

$YEAR = 2099;                 // sentinel period — isolated from production rows
$period = ['mode' => 'year', 'year' => $YEAR];

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = $expected === $actual;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

$conexion = Conexion::obtener_conexion();

/** Insert one rfq row with sane required-column defaults. Returns the new id. */
function insertQuote($conexion, $year, array $o) {
  $issue = sprintf('03/15/%d', $year); // Q1, March
  $f = array_merge([
    'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'PMTEST',
    'email_code' => 'PMTEST-' . uniqid(), 'type_of_bid' => 'IT',
    'issue_date' => $issue, 'end_date' => $issue, 'status' => 0, 'completado' => 0,
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

/** Insert one services line item for a quote (drives the services subtotal). */
function insertService($conexion, $id_rfq, $total_price) {
  $sql = 'INSERT INTO services (id_rfq, description, quantity, unit_price, total_price)
          VALUES (:id_rfq, :description, :quantity, :unit_price, :total_price)';
  $stmt = $conexion->prepare($sql);
  $stmt->bindValue(':id_rfq', $id_rfq, PDO::PARAM_INT);
  $stmt->bindValue(':description', 'PMTEST service');
  $stmt->bindValue(':quantity', 1);
  $stmt->bindValue(':unit_price', $total_price);
  $stmt->bindValue(':total_price', $total_price);
  $stmt->execute();
  return (int)$conexion->lastInsertId();
}

$conexion->beginTransaction();
try {
  // ---- controlled dataset (12 quotes covering every bucket) ----
  $ids = [];
  // 2 awards (won) — category IT
  $ids['award1'] = insertQuote($conexion, $YEAR, ['award' => 1, 'status' => 1, 'completado' => 1, 'total_price' => 100, 'type_of_bid' => 'IT']);
  $ids['award2'] = insertQuote($conexion, $YEAR, ['fullfillment' => 1, 'status' => 1, 'completado' => 1, 'total_price' => 200, 'type_of_bid' => 'IT']);
  // 2 lost
  $ids['lostP'] = insertQuote($conexion, $YEAR, ['status' => 1, 'completado' => 1, 'comments' => 'No Award - Pricing', 'total_price' => 50, 'type_of_bid' => 'Services']);
  $ids['lostT'] = insertQuote($conexion, $YEAR, ['status' => 1, 'completado' => 1, 'comments' => 'No Award - Technical', 'total_price' => 60, 'type_of_bid' => 'Services']);
  // 2 regular submitted (pending) — category Services
  $ids['sub1'] = insertQuote($conexion, $YEAR, ['status' => 1, 'completado' => 1, 'total_price' => 70, 'type_of_bid' => 'Services']);
  $ids['sub2'] = insertQuote($conexion, $YEAR, ['status' => 1, 'completado' => 1, 'total_price' => 80, 'type_of_bid' => 'Services']);
  // 1 submitted sources-sought (excluded from win/loss)
  $ids['ss'] = insertQuote($conexion, $YEAR, ['status' => 1, 'completado' => 1, 'sources_sought' => 1, 'total_price' => 90, 'type_of_bid' => 'Audio Visual']);
  // 1 not submitted (priced), 1 no bid (priced), 1 bid, 1 tbd, 1 cancelled
  $ids['ns']  = insertQuote($conexion, $YEAR, ['completado' => 1, 'comments' => 'Not submitted', 'total_price' => 10]);
  $ids['nb']  = insertQuote($conexion, $YEAR, ['completado' => 1, 'comments' => 'No Bid', 'total_price' => 5]);
  $ids['bid'] = insertQuote($conexion, $YEAR, ['completado' => 1, 'comments' => 'No comments']);
  $ids['tbd'] = insertQuote($conexion, $YEAR, ['completado' => 0, 'comments' => 'No comments']);
  $ids['can'] = insertQuote($conexion, $YEAR, ['completado' => 1, 'comments' => 'Cancelled']);

  $m = PipelineMetricsRepository::getMetrics($conexion, $period);

  // ---- KPIs ----
  echo "[KPIs]\n";
  check('total pipeline count', 12, $m['count']);
  check('totalValue sum', 665.0, (float)$m['totalValue']);
  check('submittedCount (all submitted-keys)', 7, $m['submittedCount']);
  check('awardedCount', 2, $m['awardedCount']);
  check('lostCount', 2, $m['lostCount']);
  check('pendingCount (tbd+bid+submitted+ss)', 5, $m['pendingCount']);

  // ---- status distribution ----
  echo "[Status distribution]\n";
  $byKey = [];
  foreach ($m['status'] as $s) { $byKey[$s['key']] = $s['count']; }
  check('status: award=2', 2, $byKey['award']);
  check('status: no_award_pricing=1', 1, $byKey['no_award_pricing']);
  check('status: no_award_technical=1', 1, $byKey['no_award_technical']);
  check('status: submitted=2', 2, $byKey['submitted']);
  check('status: submitted_ss=1', 1, $byKey['submitted_ss']);
  check('status: not_submitted=1', 1, $byKey['not_submitted']);
  check('status: no_bid=1', 1, $byKey['no_bid']);
  check('status: bid=1', 1, $byKey['bid']);
  check('status: tbd=1', 1, $byKey['tbd']);
  check('status: cancelled=1', 1, $byKey['cancelled']);
  check('status series has all 10 buckets', 10, count($m['status']));

  // ---- win/loss math (denom = submitted(2)+award(2)+lost(2) = 6; rate = 2/6) ----
  echo "[Win/Loss]\n";
  check('winLoss denominator', 6, $m['winLoss']['denominator']);
  check('winLoss awarded', 2, $m['winLoss']['awarded']);
  check('winLoss noAward', 2, $m['winLoss']['noAward']);
  check('winLoss pending (regular submitted only, SS excluded)', 2, $m['winLoss']['pending']);
  check('winRate = 2/6 rounded', 0.3333, $m['winRate']);
  $shares = $m['winLoss']['awarded'] + $m['winLoss']['noAward'] + $m['winLoss']['pending'];
  check('three win/loss slices sum to denominator', $m['winLoss']['denominator'], $shares);

  // ---- category breakdowns ----
  echo "[Category breakdowns]\n";
  $awardsByCat = [];
  foreach ($m['awardsByCategory'] as $c) { $awardsByCat[$c['category']] = $c['count']; }
  check('awards by category: IT=2', 2, $awardsByCat['IT'] ?? 0);
  check('awards by category: no Services awards', 0, $awardsByCat['Services'] ?? 0);
  $subByCat = [];
  foreach ($m['submittedByCategory'] as $c) { $subByCat[$c['category']] = $c['count']; }
  // Services submitted-keys: 2 regular submitted + 2 lost (lost are submitted-keys) = 4
  check('submitted by category: Services=4', 4, $subByCat['Services'] ?? 0);
  check('submitted by category: IT=2 (the 2 awards)', 2, $subByCat['IT'] ?? 0);

  // ---- pricing effort (priced = completado=1; here all but tbd = 11 priced) ----
  echo "[Pricing effort]\n";
  $pb = [];
  foreach ($m['pricing']['buckets'] as $b) { $pb[$b['key']] = $b['count']; }
  check('pricing total (completado=1)', 11, $m['pricing']['total']);
  check('pricing submitted (submitted-keys, priced)', 7, $pb['submitted']);
  check('pricing not_submitted', 1, $pb['not_submitted']);
  check('pricing no_bid', 1, $pb['no_bid']);

  // ---- drill-down ----
  echo "[Drill-down]\n";
  $d = PipelineMetricsRepository::getDrillDown($conexion, $period, ['type' => 'status', 'key' => 'award']);
  check('drill status=award returns 2', 2, count($d));
  $d2 = PipelineMetricsRepository::getDrillDown($conexion, $period, ['type' => 'winloss', 'key' => 'pending']);
  check('drill winloss=pending returns 2 (regular submitted)', 2, count($d2));
  $d3 = PipelineMetricsRepository::getDrillDown($conexion, $period, ['type' => 'category', 'bucket' => 'submitted', 'category' => 'Services']);
  check('drill category Services submitted returns 4', 4, count($d3));
  $d4 = PipelineMetricsRepository::getDrillDown($conexion, $period, ['type' => 'priced', 'key' => 'no_bid']);
  check('drill priced no_bid returns 1', 1, count($d4));
  $hasShape = !empty($d) && isset($d[0]['id'], $d[0]['code'], $d[0]['name'], $d[0]['category'], $d[0]['value'], $d[0]['status']);
  check('drill row shape has required fields', true, $hasShape);

  // ---- empty period ----
  echo "[Empty period]\n";
  $empty = PipelineMetricsRepository::getMetrics($conexion, ['mode' => 'year', 'year' => 2098]);
  check('empty period flagged', true, $empty['empty']);
  check('empty period winRate null', null, $empty['winRate']);
  check('empty period count 0', 0, $empty['count']);

  // ---- quarter/month scoping (rows are in Q1 / March) ----
  echo "[Period scoping]\n";
  $q1 = PipelineMetricsRepository::getMetrics($conexion, ['mode' => 'quarter', 'year' => $YEAR, 'quarter' => 1]);
  check('Q1 count = 12', 12, $q1['count']);
  $q2 = PipelineMetricsRepository::getMetrics($conexion, ['mode' => 'quarter', 'year' => $YEAR, 'quarter' => 2]);
  check('Q2 count = 0', 0, $q2['count']);
  $mar = PipelineMetricsRepository::getMetrics($conexion, ['mode' => 'month', 'year' => $YEAR, 'month' => 3]);
  check('March count = 12', 12, $mar['count']);
  $apr = PipelineMetricsRepository::getMetrics($conexion, ['mode' => 'month', 'year' => $YEAR, 'month' => 4]);
  check('April count = 0', 0, $apr['count']);

  // ---- services-inclusive value (bug: pipeline $ must include services subtotal) ----
  // Isolated sentinel year: one awarded quote, total_price=100, plus two services (30+20).
  // Every money figure must equal 150 = total_price + sum(services.total_price), not 100.
  echo "[Services-inclusive value]\n";
  $SVC_YEAR = 2097;
  $svcPeriod = ['mode' => 'year', 'year' => $SVC_YEAR];
  $svcAward = insertQuote($conexion, $SVC_YEAR, [
    'award' => 1, 'status' => 1, 'completado' => 1, 'total_price' => 100, 'type_of_bid' => 'Services',
  ]);
  insertService($conexion, $svcAward, 30);
  insertService($conexion, $svcAward, 20);
  $sm = PipelineMetricsRepository::getMetrics($conexion, $svcPeriod);
  check('awardedValue includes services subtotal (100+30+20)', 150.0, (float)$sm['awardedValue']);
  check('totalValue includes services subtotal',               150.0, (float)$sm['totalValue']);
  check('submittedValue includes services subtotal',           150.0, (float)$sm['submittedValue']);
  $sd = PipelineMetricsRepository::getDrillDown($conexion, $svcPeriod, ['type' => 'status', 'key' => 'award']);
  check('drill-down row value includes services subtotal', 150.0, (float)($sd[0]['value'] ?? 0));

} finally {
  $conexion->rollBack(); // leave the DB exactly as we found it
}

echo "\n==== $pass passed, $fail failed ====\n";
exit($fail === 0 ? 0 : 1);
