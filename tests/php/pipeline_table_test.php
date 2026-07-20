<?php
/**
 * Integration test for PipelineTableRepository (Bid Pipeline Metrics "Table" view).
 *
 * Covers period scoping, status filter, designated-user filter, and custom date range
 * (including an inverted range returning empty, no error) over the same rfq.created_at
 * cohort the charts use.
 *
 * Transaction-isolated (ROLLBACK). Rows live in year 2099 so the period filter matches
 * only what this test inserts (real rows carry a different/NULL created_at).
 * Run:  docker exec lamp-php84 php /var/www/html/rfq/tests/php/pipeline_table_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/routes.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Report/PipelineMetricsRepository.inc.php';
require_once $root . 'app/Report/PipelineTableRepository.inc.php';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = $expected === $actual;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

Conexion::abrir_conexion();
$c = Conexion::obtener_conexion();
$c->beginTransaction();

try {
  // --- test users (designated) ---
  $mkUser = function ($name) use ($c) {
    $stmt = $c->prepare("INSERT INTO usuarios (nombre_usuario, password, nombres, apellidos, cargo, email, status, notif_inapp, notif_email)
                         VALUES (:u, 'x', :n, 'Test', '3', :e, 1, 1, 0)");
    $stmt->execute([':u' => $name, ':n' => $name, ':e' => $name . '@test.local']);
    return (int)$c->lastInsertId();
  };
  $userA = $mkUser('pt_a_' . uniqid());
  $userB = $mkUser('pt_b_' . uniqid());

  // --- three quotes in 2099 with distinct derived statuses ---
  $mkRfq = function ($fields) use ($c) {
    $cols = array_merge([
      'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'FedBid', 'email_code' => 'PT-TEST',
      'type_of_bid' => 'IT', 'status' => 0, 'completado' => 0, 'comments' => '', 'award' => 0,
      'total_price' => 1000, 'deleted' => 0, 'name' => 'Pipeline table test quote', 'file_document' => 'a.pdf|b.xlsx',
      'created_at' => '2099-06-15 10:00:00',
    ], $fields);
    $keys = array_keys($cols);
    $sql = 'INSERT INTO rfq (' . implode(',', $keys) . ') VALUES (:' . implode(',:', $keys) . ')';
    $stmt = $c->prepare($sql);
    foreach ($cols as $k => $v) $stmt->bindValue(':' . $k, $v);
    $stmt->execute();
    return (int)$c->lastInsertId();
  };
  $qBid       = $mkRfq(['completado' => 1]);                       // 'bid'
  $qSubmitted = $mkRfq(['completado' => 1, 'status' => 1]);        // 'submitted'
  $qAward     = $mkRfq(['completado' => 1, 'award' => 1, 'usuario_designado' => $userB]); // 'award'

  $year = ['mode' => 'year', 'year' => 2099];
  $noF  = ['quoteId' => '', 'channel' => '', 'emailCode' => '', 'statuses' => [], 'bidType' => '', 'user' => ''];

  $all = PipelineTableRepository::getPage($c, $year, $noF, 0);
  check('table: 3 quotes in 2099', 3, $all['total']);

  $byId = [];
  foreach ($all['rows'] as $r) $byId[$r['id']] = $r;
  check('row carries created date', true, (bool)preg_match('#^\d{2}/\d{2}/\d{4}$#', $byId[$qBid]['created']));
  check('row status label present', 'Award', $byId[$qAward]['statusLabel']);
  check('row has no watched field (Quote Watchers removed)', false, isset($byId[$qBid]['watched']));

  // status filter
  $awardOnly = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['statuses' => ['award']]), 0);
  check('status filter award -> 1', 1, $awardOnly['total']);

  // designated-user filter (qAward is assigned to userB)
  $userFilter = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['user' => $userB]), 0);
  check('designated-user filter -> 1', 1, $userFilter['total']);

  // custom range covering June 2099
  $custom = PipelineTableRepository::getPage($c, ['mode' => 'custom', 'from' => '2099-01-01', 'to' => '2099-12-31'], $noF, 0);
  check('custom range covering the rows -> 3', 3, $custom['total']);
  // inverted range -> empty, no error
  $inverted = PipelineTableRepository::getPage($c, ['mode' => 'custom', 'from' => '2099-12-31', 'to' => '2099-01-01'], $noF, 0);
  check('inverted custom range -> 0', 0, $inverted['total']);

  // distinct channels helper (used by the Channel filter dropdown)
  $channels = PipelineTableRepository::getDistinctChannels($c);
  $values = array_column($channels, 'value');
  check('distinct channels includes our test channel', true, in_array('FedBid', $values, true));

} finally {
  $c->rollBack();
  Conexion::cerrar_conexion();
}

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
