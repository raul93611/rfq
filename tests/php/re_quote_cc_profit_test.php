<?php
/**
 * Integration test for: Re-Quote Total Profit wrong after switching Services
 * Payment Terms to Net 30/CC (bugs/re-quote-cc-profit-mismatch.md).
 *
 * Root cause: Rfq::obtener_re_quote_total_cost() summed re_quote_services.total_price
 * as the services-cost term, while Rfq::obtener_quote_total_price() (the price side of
 * the same profit subtraction) sums the original, untouched `services` table. On Save,
 * ReQuoteServiceRepository::calc_items_with_CC() inflates only re_quote_services.total_price
 * by 3% for Net 30/CC, so that markup no longer cancels between price and cost — it comes
 * straight off Total Profit as a phantom cost, even though services are meant to be
 * margin-neutral on the re-quote page, same as on the main Quote page (confirmed
 * intent — see bug file).
 *
 * Fix: obtener_re_quote_total_cost() now adds getTotalQuoteServices() (the same source
 * obtener_quote_total_price() uses) instead of the re_quote_services total, so services
 * cancel out of the re-quote profit calc regardless of payment terms — exactly like the
 * main Quote page.
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

  // Re-quote: items cost re-solicited to 800 (unchanged), services copied at Net 30 (100).
  $stmt = $c->prepare("INSERT INTO re_quotes (id_rfq, total_cost, total_price, payment_terms, taxes,
      profit, additional, shipping_cost, shipping, services_payment_term)
      VALUES (:rfq, 800.00, 1000.00, 'Net 30', 0, 0, '', 0, '', 'Net 30')");
  $stmt->execute([':rfq' => $rfqId]);
  $reQuoteId = (int) $c->lastInsertId();

  $stmt = $c->prepare("INSERT INTO re_quote_services (id_re_quote, description, quantity, unit_price, total_price)
      VALUES (:rq, 'Install labor', 1, 100.00, 100.00)");
  $stmt->execute([':rq' => $reQuoteId]);

  $quote = RepositorioRfq::obtener_cotizacion_por_id($c, $rfqId);

  echo "[baseline: re-quote services still at Net 30, matching original quote]\n";
  check('items+services price is $1100 (Total Price)', 1100.00, $quote->obtener_quote_total_price());
  check('re-quote profit is $200 (1000 items price - 800 items cost, services cancel)', 200.00, $quote->obtener_re_quote_profit());

  echo "\n[switching re-quote Services Payment Terms to Net 30/CC and saving]\n";
  ReQuoteServiceRepository::calc_items_with_CC($c, 'Net 30/CC', $reQuoteId);

  check('re_quote_services total_price inflated to $103 by the CC markup', 103.00, ReQuoteServiceRepository::get_total($c, $reQuoteId));
  check('Total Price is unaffected by the re-quote services CC toggle', 1100.00, $quote->obtener_quote_total_price());
  check('re-quote profit stays $200 — CC on services must not move profit, same as the main Quote page', 200.00, $quote->obtener_re_quote_profit());

} finally {
  $c->rollBack();
  Conexion::cerrar_conexion();
}

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
