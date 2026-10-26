<?php
/**
 * Integration test for Automatic Sales Commission (features/automatic-sales-commission.md).
 *
 * Covers:
 *   1  Moving and Logistics / Professional Services → $0 "not eligible", whatever the profit
 *   2  price $0 → $0; fulfillment profit % 5.99 → $0 "under threshold"; 6.00 and 5.996 qualify
 *   3  smaller of re-quote / fulfillment profit gets 3%, rounded to cents; tie → fulfillment
 *   4  zero or negative basis → $0 (never negative); no re-quote → fulfillment profit; blank type eligible
 *   5  forQuote() reads the quote + re-quote from the DB; set_sales_commission() freezes the result
 *      and keeps the legacy `sales_commission` strings; later cost edits don't move it; re-invoicing recalculates
 *   6  amountSql(): saved amount for auto quotes, the old manual CASE for legacy (NULL) quotes
 *   7  audit message carries the amount and basis, dynamic values escaped
 *   8  Sales Commission report, its Excel export and the monthly projection agree on the same amount
 *      for an auto quote and still use the old formula for a legacy one
 *
 * Transaction-isolated: everything runs inside a ROLLBACK, so no row survives. No DDL runs here —
 * apply sql/sales_commission_auto_migration.sql first.
 * Run:  docker exec lamp-php83 php /var/www/html/rfq/tests/php/sales_commission_calculator_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/routes.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Quote/Rfq.inc.php';
require_once $root . 'app/Quote/RepositorioRfq.inc.php';
require_once $root . 'app/Quote/AuditTrail.inc.php';
require_once $root . 'app/Quote/AuditTrailRepository.inc.php';
require_once $root . 'app/SalesCommission/SalesCommissionCalculator.inc.php';
require_once $root . 'app/Report/Report.inc.php';
require_once $root . 'app/Utilities/ExcelRepository.inc.php';
require_once $root . 'app/YearlyProjection/YearlyProjectionRepository.inc.php';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  if ($expected === $actual) { $pass++; echo "  PASS  $label\n"; }
  else { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

Conexion::abrir_conexion();
$c = Conexion::obtener_conexion();
$c->beginTransaction();

$q = function ($sql, array $p = []) use ($c) { $s = $c->prepare($sql); $s->execute($p); return $s; };
$one = function ($sql, array $p = []) use ($q) { return $q($sql, $p)->fetch(PDO::FETCH_ASSOC); };

function mkQuote($c, array $o = []) {
  $d = array_merge([
    'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'FBO', 'email_code' => 'T-' . uniqid(),
    'type_of_bid' => 'Services', 'issue_date' => '', 'end_date' => '', 'status' => 0, 'completado' => 0,
    'total_cost' => 0, 'total_price' => 0, 'comments' => 'No comments', 'award' => 0, 'deleted' => 0,
    'name' => 'Commission test', 'client' => 'Test Agency',
  ], $o);
  $cols = implode(',', array_keys($d));
  $ph = implode(',', array_map(function ($k) { return ':' . $k; }, array_keys($d)));
  $s = $c->prepare("INSERT INTO rfq ($cols) VALUES ($ph)");
  $s->execute($d);
  return (int)$c->lastInsertId();
}
function mkReQuote($c, $idRfq, $totalCost) {
  $s = $c->prepare("INSERT INTO re_quotes (id_rfq, total_cost, total_price, payment_terms, taxes, profit, shipping_cost, shipping) VALUES (:i, :c, 0, 'Net 30', 0, 0, 0, '')");
  $s->execute([':i' => $idRfq, ':c' => $totalCost]);
}

/** price, fulfillment cost, re-quote cost (null = no re-quote), type → calculation */
function calc($price, $fulfillmentCost, $requoteCost = null, $type = 'AV - Maintenance') {
  return SalesCommissionCalculator::calculate($type, $price, $fulfillmentCost, $requoteCost);
}

try {
  // ---------------------------------------------------------------- 1. not eligible
  echo "-- 1. not eligible types\n";
  foreach (['Moving and Logistics', 'Professional Services'] as $type) {
    $r = calc(10000, 5000, 4000, $type);
    check("$type → \$0", 0.0, $r['amount']);
    check("$type → reason not_eligible_type", 'not_eligible_type', $r['reason']);
    check("$type → legacy string 'No commission'", 'No commission', $r['sales_commission']);
  }
  check('not-eligible beats under-threshold', 'not_eligible_type', calc(10000, 9900, null, 'Moving and Logistics')['reason']);

  // ---------------------------------------------------------------- 2. price + threshold
  echo "-- 2. price and 6% threshold\n";
  $r = calc(0, 0);
  check('price $0 → $0', 0.0, $r['amount']);
  check('price $0 → reason no_price', 'no_price', $r['reason']);
  $r = calc(10000, 9401);   // 5.99%
  check('5.99% → $0', 0.0, $r['amount']);
  check('5.99% → under_threshold', 'under_threshold', $r['reason']);
  check('5.99% → pct kept for the card', 5.99, $r['fulfillment_pct']);
  $r = calc(10000, 9400);   // exactly 6.00%
  check('exactly 6.00% qualifies', 'fulfillment_smaller', $r['reason']);
  check('exactly 6.00% → 3% of $600', 18.0, $r['amount']);
  $r = calc(100000, 94004); // 5.996% → displays 6.00
  check('5.996% (displays 6.00) qualifies', 'fulfillment_smaller', $r['reason']);
  check('5.996% → pct is the 2-decimal value', 6.0, $r['fulfillment_pct']);
  $r = calc(10000, 10500, 4000);  // fulfillment loss: under threshold even though re-quote is healthy
  check('negative fulfillment % → under_threshold', 'under_threshold', $r['reason']);

  // ---------------------------------------------------------------- 3. smaller profit
  echo "-- 3. smaller of the two profits\n";
  $r = calc(100000, 90000, 92000);  // fulfillment 10,000 ; re-quote 8,000
  check('re-quote smaller → requote_smaller', 'requote_smaller', $r['reason']);
  check('re-quote smaller → 3% of re-quote profit', 240.0, $r['amount']);
  check('re-quote smaller → basis profit', 8000.0, $r['basis_profit']);
  check('re-quote smaller → legacy string Same commission', 'Same commission', $r['sales_commission']);
  $r = calc(100000, 92000, 90000);  // fulfillment 8,000 ; re-quote 10,000
  check('fulfillment smaller → fulfillment_smaller', 'fulfillment_smaller', $r['reason']);
  check('fulfillment smaller → 3% of fulfillment profit', 240.0, $r['amount']);
  check('fulfillment smaller → legacy string Other commission', 'Other commission', $r['sales_commission']);
  $r = calc(100000, 90000, 90000);
  check('tie → fulfillment profit', 'fulfillment_smaller', $r['reason']);
  $r = calc(33333.33, 30000, null);  // 3333.33 * 0.03 = 99.9999
  check('amount rounded to cents', 100.0, $r['amount']);
  $r = calc(10000, 9000, 9500, 'RFQ - VAR');
  check('profits reported for the breakdown', [1000.0, 10.0, 500.0, 5.0], [$r['fulfillment_profit'], $r['fulfillment_pct'], $r['requote_profit'], $r['requote_pct']]);

  // ---------------------------------------------------------------- 4. floors, no re-quote, blank type
  echo "-- 4. zero/negative basis, no re-quote, blank type\n";
  $r = calc(10000, 9000, 10000);    // re-quote profit 0
  check('zero basis → $0', 0.0, $r['amount']);
  check('zero basis → reason no_profit', 'no_profit', $r['reason']);
  $r = calc(10000, 9000, 10500);    // re-quote profit -500
  check('negative basis → $0, never negative', 0.0, $r['amount']);
  check('negative basis is kept (before the floor)', -500.0, $r['basis_profit']);
  check('negative basis → legacy string No commission', 'No commission', $r['sales_commission']);
  $r = calc(10000, 9000, null);
  check('no re-quote → fulfillment profit is the basis', 30.0, $r['amount']);
  check('no re-quote → no re-quote profit reported', null, $r['requote_profit']);
  foreach ([null, '', '  '] as $blank) {
    check('blank type ' . var_export($blank, true) . ' is eligible', 'fulfillment_smaller', calc(10000, 9000, null, $blank)['reason']);
  }

  // ---------------------------------------------------------------- 5. DB-backed
  echo "-- 5. forQuote + freezing\n";
  $id = mkQuote($c, ['total_price' => 100000, 'total_fulfillment' => 92000, 'type_of_contract' => 'AV - Installation']);
  mkReQuote($c, $id, 90000);
  $r = SalesCommissionCalculator::forQuote($c, $id);
  check('forQuote reads price, fulfillment and re-quote', ['fulfillment_smaller', 240.0], [$r['reason'], $r['amount']]);
  $idNoRq = mkQuote($c, ['total_price' => 10000, 'total_fulfillment' => 9000]);
  $n = SalesCommissionCalculator::forQuote($c, $idNoRq);
  check('forQuote with no re-quote and no type', ['fulfillment_smaller', 30.0, null], [$n['reason'], $n['amount'], $n['requote_profit']]);

  RepositorioRfq::set_sales_commission($c, '10/26/2026', $r, 'Paid in November', $id);
  $row = $one('SELECT * FROM rfq WHERE id = :i', [':i' => $id]);
  check('amount frozen', '240.00', $row['sales_commission_amount']);
  check('basis profit frozen', '8000.00', $row['sales_commission_basis_profit']);
  check('fulfillment % frozen', '8.00', $row['sales_commission_fulfillment_pct']);
  check('reason frozen', 'fulfillment_smaller', $row['sales_commission_reason']);
  check('legacy string written for report filters', 'Other commission', $row['sales_commission']);
  check('comment saved', 'Paid in November', $row['sales_commission_comment']);
  check('invoice date saved', '2026-10-26', $row['invoice_date']);

  $q('UPDATE rfq SET total_fulfillment = 50000 WHERE id = :i', [':i' => $id]);
  check('later cost edit leaves the saved amount alone', '240.00', $one('SELECT sales_commission_amount a FROM rfq WHERE id = :i', [':i' => $id])['a']);
  RepositorioRfq::set_sales_commission($c, '11/02/2026', SalesCommissionCalculator::forQuote($c, $id), '', $id);
  $row = $one('SELECT * FROM rfq WHERE id = :i', [':i' => $id]);
  check('invoicing again recalculates from current numbers', ['300.00', 'requote_smaller'], [$row['sales_commission_amount'], $row['sales_commission_reason']]);

  // ---------------------------------------------------------------- 6. SQL expression
  echo "-- 6. amountSql: saved amount vs legacy CASE\n";
  $amountOf = function ($idRfq) use ($one) {
    return $one('SELECT ' . SalesCommissionCalculator::amountSql('r', 'rq') . ' AS amt FROM rfq r LEFT JOIN re_quotes rq ON r.id = rq.id_rfq WHERE r.id = :i', [':i' => $idRfq])['amt'];
  };
  check('auto quote → saved amount', '300.00', $amountOf($id));
  $legacy = fn($string) => mkQuote($c, ['total_price' => 100000, 'total_fulfillment' => 92000, 'sales_commission' => $string]);
  $idSame = $legacy('Same commission'); mkReQuote($c, $idSame, 90000);
  check('legacy Same commission → 3% of re-quote profit', '300.00', $amountOf($idSame));
  check('legacy Other commission → 3% of fulfillment profit', '240.00', $amountOf($legacy('Other commission')));
  check('legacy No commission → 0', '0.00', $amountOf($legacy('No commission')));
  check('legacy unset → NULL', null, $amountOf($legacy(null)));
  $zero = mkQuote($c, ['total_price' => 100000, 'total_fulfillment' => 92000, 'sales_commission' => 'Other commission']);
  $q("UPDATE rfq SET sales_commission_amount = 0 WHERE id = :i", [':i' => $zero]);
  check('auto $0 is not mistaken for legacy', '0.00', $amountOf($zero));

  // ---------------------------------------------------------------- 7. audit message
  echo "-- 7. audit message\n";
  $msg = AuditTrailRepository::invoiceMessage(calc(100000, 92000, 90000));
  check('message keeps the status text', true, strpos($msg, 'The quote was <b>Invoice</b>') === 0);
  check('message carries amount', true, strpos($msg, '<b>$240.00</b>') !== false);
  check('message carries basis', true, strpos($msg, 'fulfillment profit $8,000.00') !== false);
  $msg = AuditTrailRepository::invoiceMessage(calc(100000, 10000, null, '<img src=x onerror=alert(1)>'));
  check('no raw dynamic HTML in message', false, strpos($msg, '<img') !== false);
  $msg = AuditTrailRepository::invoiceMessage(calc(100000, 10000, null, 'Moving and Logistics'));
  check('zero commission message carries the reason', true, strpos($msg, '<b>$0.00</b>') !== false && strpos($msg, 'Not eligible') !== false);

  // ---------------------------------------------------------------- 8. report / excel / projection
  echo "-- 8. report, Excel and projection agree\n";
  $invoiced = ['invoice' => 1, 'invoice_date' => '2099-03-10', 'fulfillment_pending' => 0, 'total_price' => 100000, 'total_fulfillment' => 92000];
  $auto = mkQuote($c, $invoiced + ['name' => 'Auto SC']);
  mkReQuote($c, $auto, 90000);
  RepositorioRfq::set_sales_commission($c, '03/10/2099', SalesCommissionCalculator::forQuote($c, $auto), '', $auto); // 8,000 vs 10,000 → $240
  $q('UPDATE rfq SET total_fulfillment = 50000 WHERE id = :i', [':i' => $auto]);                               // later edit: must not move
  $old = mkQuote($c, $invoiced + ['name' => 'Legacy SC', 'sales_commission' => 'Same commission']);
  mkReQuote($c, $old, 90000);                                                                                     // legacy: 3% of 10,000 = $300
  $q('INSERT INTO yearly_projections (year) VALUES (2099)');
  $yearId = (int)$c->lastInsertId();
  $q('INSERT INTO monthly_projections (yearly_projection_id, month, projected_amount) VALUES (:y, 3, 0)', [':y' => $yearId]);
  $monthId = (int)$c->lastInsertId();

  $amounts = function (array $rows) use ($auto, $old) {
    $by = []; foreach ($rows as $r) { $by[(int)$r['id']] = (float)$r['sales_commission_amount']; }
    return [$by[$auto] ?? null, $by[$old] ?? null];
  };
  check('report (monthly): auto = saved, legacy = old formula', [240.0, 300.0], $amounts(Report::getSalesCommissionReport($c, 0, 500, '', 0, 'ASC', 'monthly', 1, 3, 2099)));
  check('report (yearly): same amounts', [240.0, 300.0], $amounts(Report::getSalesCommissionReport($c, 0, 500, '', 0, 'ASC', 'yearly', 1, 3, 2099)));
  check('Excel export (monthly): same amounts', [240.0, 300.0], $amounts(ExcelRepository::getSalesCommissionReport($c, 'monthly', 1, 3, 2099)));
  $totals = YearlyProjectionRepository::getMonthTotals($c, $monthId);
  check('monthly projection total = saved + legacy', '540.00', $totals[0]['sum_sales_commission'] ?? null);
  $rows = YearlyProjectionRepository::getMonth($c, 0, 500, '', 1, 'ASC', $monthId);
  $projected = []; foreach ($rows as $r) { $projected[(int)$r['id_quote']] = (float)$r['sales_commission']; }
  check('monthly projection rows: same amounts', [240.0, 300.0], [$projected[$auto] ?? null, $projected[$old] ?? null]);
} catch (Throwable $e) {
  $fail++;
  echo "  FAIL  unexpected exception: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}

$c->rollBack();
echo "\n$pass passed, $fail failed\n";
exit($fail ? 1 : 0);
