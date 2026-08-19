<?php
/**
 * Characterization test for Rfq::obtener_re_quote_total_cost()'s services term.
 *
 * A prior fix attempt (reverted) assumed re-quote services should always be
 * margin-neutral, like on the main Quote page, and made this method add
 * getTotalQuoteServices() (the original `services` table) instead of
 * ReQuoteServiceRepository::get_total() (the `re_quote_services` table). That
 * assumption was wrong: per commit b3fb2904 ("Services in the Re-Quote",
 * 2022-09-24 — pre-dating any AI involvement in this repo, and unchanged ever
 * since), re-quote services are DELIBERATELY their own independent cost line,
 * exactly mirroring how items work — the client-facing price stays pinned to
 * the original quote's `services` table, while `re_quote_services` is the
 * re-solicited cost estimate. A Net 30/CC markup on re_quote_services is a
 * real cost, same as the items-side CC fee, and SHOULD move Total Profit.
 *
 * This test locks in that intended behavior so it doesn't get "fixed" away
 * again. The actual bug reported against quote #118063 (Total Profit jumping
 * to a value disconnected from any live edit after toggling Net 30/CC) was
 * the bottom summary bar being frozen, page-load-only PHP — see the live-bar
 * wiring in js/reQuote.js / plantillas/re_quote/re_quote.inc.php instead.
 *
 * Transaction-isolated (ROLLBACK).
 * Run:  docker exec lamp-php83 php /var/www/html/rfq/tests/php/re_quote_cc_profit_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Service/ServiceRepository.inc.php';
require_once $root . 'app/Quote/RepositorioRfq.inc.php';
require_once $root . 'app/Quote/Rfq.inc.php';
require_once $root . 'app/ReQuote/ReQuote.inc.php';
require_once $root . 'app/ReQuote/ReQuoteRepository.inc.php';
require_once $root . 'app/ReQuote/ReQuoteService.inc.php';
require_once $root . 'app/ReQuote/ReQuoteServiceRepository.inc.php';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = abs($expected - $actual) < 0.001;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

Conexion::abrir_conexion();
$c = Conexion::obtener_conexion();
$c->beginTransaction();

try {
  $uname = 'rqcc_test_' . uniqid();
  $stmt = $c->prepare("INSERT INTO usuarios (nombre_usuario, password, nombres, apellidos, cargo, email, status)
                       VALUES (:u, 'x', 'ReQuote', 'Tester', '3', :e, 1)");
  $stmt->execute([':u' => $uname, ':e' => $uname . '@test.local']);
  $userId = (int) $c->lastInsertId();

  // Original quote: items total 1000/800 (price/cost), services 100 at Net 30.
  $stmt = $c->prepare("INSERT INTO rfq (id_usuario, usuario_designado, canal, email_code, type_of_bid,
      issue_date, end_date, status, completado, comments, award, payment_terms, address, ship_to,
      ship_via, taxes, profit, additional, shipping_cost, shipping, fullfillment, contract_number,
      services_payment_term, total_price, total_cost, deleted, name)
      VALUES (:u, :u, 'FedBid', 'RQCC-TEST', 'Services', '', '', 0, 0, '', 0, 'Net 30', '', '',
      '', 0, 0, '', 0, '', 0, '', 'Net 30', 1000.00, 800.00, 0, 'Re-Quote CC profit test quote')");
  $stmt->execute([':u' => $userId]);
  $rfqId = (int) $c->lastInsertId();

  $stmt = $c->prepare("INSERT INTO services (id_rfq, description, quantity, unit_price, total_price)
      VALUES (:rfq, 'Install labor', 1, 100.00, 100.00)");
  $stmt->execute([':rfq' => $rfqId]);

  // Re-quote: items cost re-solicited to 800 (unchanged), services copied at Net 30 (100),
  // matching what ReQuoteRepository::create_re_quote() does on a fresh re-quote.
  $stmt = $c->prepare("INSERT INTO re_quotes (id_rfq, total_cost, total_price, payment_terms, taxes,
      profit, additional, shipping_cost, shipping, services_payment_term)
      VALUES (:rfq, 800.00, 1000.00, 'Net 30', 0, 0, '', 0, '', 'Net 30')");
  $stmt->execute([':rfq' => $rfqId]);
  $reQuoteId = (int) $c->lastInsertId();

  $stmt = $c->prepare("INSERT INTO re_quote_services (id_re_quote, description, quantity, unit_price, total_price)
      VALUES (:rq, 'Install labor', 1, 100.00, 100.00)");
  $stmt->execute([':rq' => $reQuoteId]);

  $quote = RepositorioRfq::obtener_cotizacion_por_id($c, $rfqId);

  echo "[baseline: re-quote services match the original quote's services 1:1, freshly copied]\n";
  check('Total Price is $1100 (1000 items + 100 original services)', 1100.00, $quote->obtener_quote_total_price());
  check('re-quote profit is $200 (1000+100 price - 800+100 cost)', 200.00, $quote->obtener_re_quote_profit());

  echo "\n[switching re-quote Services Payment Terms to Net 30/CC and saving]\n";
  ReQuoteServiceRepository::calc_items_with_CC($c, 'Net 30/CC', $reQuoteId);

  check('re_quote_services total_price inflated to $103 by the CC markup', 103.00, ReQuoteServiceRepository::get_total($c, $reQuoteId));
  check('Total Price (client-facing, from the original quote) is unaffected by the re-quote CC toggle', 1100.00, $quote->obtener_quote_total_price());
  check('re-quote profit drops to $197 — CC is a real re-solicited cost, same as it is for items', 197.00, $quote->obtener_re_quote_profit());

  echo "\n[an independent re-costing edit to a re_quote_services row also moves profit, as intended]\n";
  $svc = ReQuoteServiceRepository::get_services($c, $reQuoteId)[0];
  ReQuoteServiceRepository::edit_service($c, $svc->get_id(), $svc->get_description(), 1, 130.00, 130.00);
  check('re-quote profit reflects the re-costed service (1100 - (800+130) = 170)', 170.00, $quote->obtener_re_quote_profit());

} finally {
  $c->rollBack();
  Conexion::cerrar_conexion();
}

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
