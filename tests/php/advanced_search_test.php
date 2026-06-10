<?php
/**
 * Integration test for RepositorioRfq advanced search
 * (getAdvancedSearchedQuotes / getAdvancedSearchedQuotesCount).
 *
 * Inserts a controlled set of quotes scoped by a unique client marker (the
 * client filter doubles as the isolation fence, so every assertion also
 * exercises AND-combination), runs the filtered queries, then ROLLS BACK.
 *
 * Run:  docker exec lamp-php83 php /var/www/html/rfq/tests/php/advanced_search_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Quote/Rfq.inc.php';
require_once $root . 'app/Quote/RepositorioRfq.inc.php';
require_once $root . 'app/Report/PipelineMetricsRepository.inc.php';

$MARK = 'ADVTESTCLIENT';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = $expected === $actual;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

/** Base filter set: everything off except the isolation client marker. */
function f(array $overrides = []) {
  global $MARK;
  return array_merge([
    'statuses' => [], 'user' => '', 'bid_type' => '', 'contract_type' => '',
    'date_field' => 'created', 'date_from' => '', 'date_to' => '',
    'price_min' => '', 'price_max' => '', 'client' => $MARK, 'state' => '',
  ], $overrides);
}

function advCount($conexion, $term, array $filters) {
  return (int)RepositorioRfq::getAdvancedSearchedQuotesCount($conexion, $term, $filters);
}
function advRows($conexion, $term, array $filters) {
  return RepositorioRfq::getAdvancedSearchedQuotes($conexion, 0, 50, 0, 'desc', $term, $filters);
}

$conexion = Conexion::obtener_conexion();

function insertQuote($conexion, array $o) {
  global $MARK;
  $f = array_merge([
    'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'ADVTEST',
    'email_code' => 'ADVTEST-' . uniqid(), 'type_of_bid' => 'IT',
    'created_at' => '2099-03-15 00:00:00',
    'issue_date' => '03/15/2099', 'end_date' => '03/15/2099', 'status' => 0, 'completado' => 0,
    'comments' => 'No comments', 'award' => 0, 'payment_terms' => 'Net 30',
    'address' => '', 'ship_to' => '', 'ship_via' => '', 'taxes' => 0, 'profit' => 0,
    'additional' => '', 'shipping_cost' => 0, 'shipping' => '', 'fullfillment' => 0,
    'contract_number' => '', 'sources_sought' => 0, 'total_price' => 0,
    'invoice' => 0, 'submitted_invoice' => 0, 'deleted' => 0, 'name' => 'Adv Search Test',
    'client' => $MARK, 'state' => 'FL', 'type_of_contract' => 'Purchase Order',
  ], $o);
  $cols = array_keys($f);
  $ph = array_map(fn($c) => ':' . $c, $cols);
  $sql = 'INSERT INTO rfq (' . implode(',', $cols) . ') VALUES (' . implode(',', $ph) . ')';
  $stmt = $conexion->prepare($sql);
  foreach ($f as $c => $v) { $stmt->bindValue(':' . $c, $v); }
  $stmt->execute();
  return (int)$conexion->lastInsertId();
}

function insertService($conexion, $id_rfq, $total_price) {
  $stmt = $conexion->prepare('INSERT INTO services (id_rfq, description, quantity, unit_price, total_price)
          VALUES (:id_rfq, :d, 1, :p, :p2)');
  $stmt->bindValue(':id_rfq', $id_rfq, PDO::PARAM_INT);
  $stmt->bindValue(':d', 'ADVTEST service');
  $stmt->bindValue(':p', $total_price);
  $stmt->bindValue(':p2', $total_price);
  $stmt->execute();
}

$conexion->beginTransaction();
try {
  // ---- controlled dataset: 5 quotes, one per axis under test ----
  $ids = [];
  // award — IT / PO / FL, product 100 + service 50 = 150, full lifecycle dates
  $ids['award'] = insertQuote($conexion, [
    'award' => 1, 'status' => 1, 'completado' => 1, 'total_price' => 100,
    'email_code' => 'ADVKW-XYZ123',
    'fecha_submitted' => '2099-03-20', 'fecha_award' => '2099-04-01',
  ]);
  insertService($conexion, $ids['award'], 50);
  // submitted — Services / GA, price 200
  $ids['sub'] = insertQuote($conexion, [
    'status' => 1, 'completado' => 1, 'total_price' => 200, 'type_of_bid' => 'Services',
    'state' => 'GA', 'created_at' => '2099-05-10 00:00:00', 'fecha_submitted' => '2099-05-15',
  ]);
  // no bid — priced 0
  $ids['nobid'] = insertQuote($conexion, [
    'completado' => 1, 'comments' => 'No Bid', 'created_at' => '2099-06-01 00:00:00',
  ]);
  // tbd — TX / GSA Schedule / distinct designated user, price 300
  $ids['tbd'] = insertQuote($conexion, [
    'completado' => 0, 'total_price' => 300, 'state' => 'TX',
    'type_of_contract' => 'GSA Schedule', 'usuario_designado' => 999999,
    'created_at' => '2099-07-01 00:00:00',
  ]);
  // submitted sources-sought
  $ids['ss'] = insertQuote($conexion, [
    'status' => 1, 'completado' => 1, 'sources_sought' => 1, 'total_price' => 90,
    'fecha_submitted' => '2099-03-25',
  ]);
  // deleted quote — must never appear
  insertQuote($conexion, ['deleted' => 1]);

  echo "[Isolation / no filters]\n";
  check('client marker alone matches the 5 live test quotes', 5, advCount($conexion, '', f()));
  check('deleted quotes are excluded', 5, count(advRows($conexion, '', f())));

  echo "[Status filter]\n";
  check('single status: award', 1, advCount($conexion, '', f(['statuses' => ['award']])));
  $rows = advRows($conexion, '', f(['statuses' => ['award']]));
  check('award row carries derived_status', 'award', $rows[0]['derived_status'] ?? null);
  check('multi-select is a union (submitted+submitted_ss)', 2, advCount($conexion, '', f(['statuses' => ['submitted', 'submitted_ss']])));
  check('status no_bid', 1, advCount($conexion, '', f(['statuses' => ['no_bid']])));
  check('status tbd', 1, advCount($conexion, '', f(['statuses' => ['tbd']])));

  echo "[Simple field filters]\n";
  check('bid type IT', 4, advCount($conexion, '', f(['bid_type' => 'IT'])));  // award + nobid + tbd + ss
  check('bid type Services', 1, advCount($conexion, '', f(['bid_type' => 'Services'])));
  check('contract type GSA Schedule', 1, advCount($conexion, '', f(['contract_type' => 'GSA Schedule'])));
  check('designated user', 1, advCount($conexion, '', f(['user' => '999999'])));
  check('state LIKE (FL)', 3, advCount($conexion, '', f(['state' => 'FL'])));

  echo "[Date range]\n";
  check('created range May–Jun', 2, advCount($conexion, '', f(['date_from' => '2099-05-01', 'date_to' => '2099-06-30'])));
  check('created from-only', 3, advCount($conexion, '', f(['date_from' => '2099-05-01'])));
  check('submitted field: NULLs excluded', 3, advCount($conexion, '', f(['date_field' => 'submitted', 'date_from' => '2099-01-01'])));
  check('awarded field: only the award', 1, advCount($conexion, '', f(['date_field' => 'awarded', 'date_from' => '2099-01-01'])));
  check('inverted date range is empty, not an error', 0, advCount($conexion, '', f(['date_from' => '2099-12-31', 'date_to' => '2099-01-01'])));

  echo "[Price range — product + services]\n";
  check('min 150 includes the 100+50 award (services join)', 3, advCount($conexion, '', f(['price_min' => '150'])));
  check('max 100 (0, 90 rows)', 2, advCount($conexion, '', f(['price_max' => '100'])));
  check('inverted price range is empty, not an error', 0, advCount($conexion, '', f(['price_min' => '1000', 'price_max' => '10'])));
  $rows = advRows($conexion, '', f(['statuses' => ['award']]));
  check('row total_price = product + services', 150.0, (float)$rows[0]['total_price']);

  echo "[Keyword + filters AND-combine]\n";
  check('keyword alone (unique email_code)', 1, advCount($conexion, 'ADVKW-XYZ123', f()));
  check('keyword + non-matching status = 0', 0, advCount($conexion, 'ADVKW-XYZ123', f(['statuses' => ['tbd']])));
  check('keyword + matching status = 1', 1, advCount($conexion, 'ADVKW-XYZ123', f(['statuses' => ['award']])));

  echo "[Pagination + ordering]\n";
  $rows = advRows($conexion, '', f());
  check('default order id desc (newest first)', $ids['ss'], (int)$rows[0]['id']);
  $page = RepositorioRfq::getAdvancedSearchedQuotes($conexion, 0, 2, 0, 'desc', '', f());
  check('LIMIT respected', 2, count($page));

  echo "[Row shape]\n";
  $row = advRows($conexion, '', f(['statuses' => ['submitted_ss']]))[0];
  foreach (['id', 'email_code', 'contract_number', 'nombre_usuario', 'type_of_bid', 'comments', 'total_price', 'derived_status'] as $k) {
    check("row has '$k'", true, array_key_exists($k, $row));
  }
  check('ss row derived_status', 'submitted_ss', $row['derived_status']);
} finally {
  $conexion->rollBack();
}

echo "\nRESULT: $pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
