<?php
/**
 * Regression test for bugs/re-quote-pdf-missing-report-total.md.
 *
 * The re-quote PDF ("Internal Pricing Reference") showed per-table totals for
 * Items and Services separately, but never the combined report-wide Total
 * Price / Total Profit / Profit % that the web app's sticky bottom bar shows
 * (js/reQuote.js:34-41). Covers:
 *   • herramientas/pdfTemplates/re_quote.inc.php renders a "Report Total"
 *     section after the Services table
 *   • its values match Rfq::obtener_quote_total_price() /
 *     obtener_re_quote_profit() / obtener_re_quote_profit_percentage() —
 *     the same source of truth already used by the web app's bottom bar
 *
 * Transaction-isolated (ROLLBACK), same pattern as re_quote_cc_profit_test.php.
 * Run:  docker exec lamp-php83 php /var/www/html/rfq/tests/php/re_quote_pdf_report_total_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Service/ServiceRepository.inc.php';
require_once $root . 'app/Quote/RepositorioRfq.inc.php';
require_once $root . 'app/Quote/RepositorioItem.inc.php';
require_once $root . 'app/Quote/Rfq.inc.php';
require_once $root . 'app/ReQuote/ReQuote.inc.php';
require_once $root . 'app/ReQuote/ReQuoteRepository.inc.php';
require_once $root . 'app/ReQuote/ReQuoteItem.inc.php';
require_once $root . 'app/ReQuote/ReQuoteItemRepository.inc.php';
require_once $root . 'app/ReQuote/ReQuoteService.inc.php';
require_once $root . 'app/ReQuote/ReQuoteServiceRepository.inc.php';
require_once $root . 'app/User/Usuario.inc.php';
require_once $root . 'app/User/RepositorioUsuario.inc.php';
require_once $root . 'app/Utilities/ProposalRepository.inc.php';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = is_numeric($expected) && is_numeric($actual) ? abs($expected - $actual) < 0.001 : $expected === $actual;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

/** Renders the re-quote PDF template with the same variables pdf_re_quote.php sets up. */
function renderReQuotePdf($root, $cotizacion, $re_quote, $re_quote_items, $re_quote_services, $total_services, $items, $usuario_designado) {
  $fecha_completado = date("m/d/Y");
  $expiration_date = date("m/d/Y");
  ob_start();
  include $root . 'herramientas/pdfTemplates/re_quote.inc.php';
  return ob_get_clean();
}

Conexion::abrir_conexion();
$c = Conexion::obtener_conexion();
$c->beginTransaction();

try {
  $uname = 'rqpdf_test_' . uniqid();
  $stmt = $c->prepare("INSERT INTO usuarios (nombre_usuario, password, nombres, apellidos, cargo, email, status)
                       VALUES (:u, 'x', 'ReQuotePdf', 'Tester', '3', :e, 1)");
  $stmt->execute([':u' => $uname, ':e' => $uname . '@test.local']);
  $userId = (int) $c->lastInsertId();

  // Original quote: items 1000/800 (price/cost), services 100 at Net 30 — same fixture shape as re_quote_cc_profit_test.php.
  $stmt = $c->prepare("INSERT INTO rfq (id_usuario, usuario_designado, canal, email_code, type_of_bid,
      issue_date, end_date, status, completado, comments, award, payment_terms, address, ship_to,
      ship_via, taxes, profit, additional, shipping_cost, shipping, fullfillment, contract_number,
      services_payment_term, total_price, total_cost, deleted, name)
      VALUES (:u, :u, 'FedBid', 'RQPDF-TEST', 'Services', '', '', 0, 0, '', 0, 'Net 30', '', '',
      '', 0, 0, '', 0, '', 0, '', 'Net 30', 1000.00, 800.00, 0, 'Re-Quote PDF report total test quote')");
  $stmt->execute([':u' => $userId]);
  $rfqId = (int) $c->lastInsertId();

  $stmt = $c->prepare("INSERT INTO services (id_rfq, description, quantity, unit_price, total_price)
      VALUES (:rfq, 'Install labor', 1, 100.00, 100.00)");
  $stmt->execute([':rfq' => $rfqId]);

  $stmt = $c->prepare("INSERT INTO re_quotes (id_rfq, total_cost, total_price, payment_terms, taxes,
      profit, additional, shipping_cost, shipping, services_payment_term)
      VALUES (:rfq, 800.00, 1000.00, 'Net 30', 0, 0, '', 0, '', 'Net 30')");
  $stmt->execute([':rfq' => $rfqId]);
  $reQuoteId = (int) $c->lastInsertId();

  $stmt = $c->prepare("INSERT INTO re_quote_services (id_re_quote, description, quantity, unit_price, total_price)
      VALUES (:rq, 'Install labor', 1, 100.00, 100.00)");
  $stmt->execute([':rq' => $reQuoteId]);

  $cotizacion = RepositorioRfq::obtener_cotizacion_por_id($c, $rfqId);
  $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq($c, $rfqId);
  $usuario_designado = RepositorioUsuario::obtener_usuario_por_id($c, $userId);
  $total_services = ReQuoteServiceRepository::get_total($c, $reQuoteId);

  // Expected combined figures — same source of truth as the web app's bottom bar (js/reQuote.js:34-41).
  $expectedTotalPrice = $cotizacion->obtener_quote_total_price();   // 1000 + 100 = 1100
  $expectedTotalProfit = $cotizacion->obtener_re_quote_profit();    // 1100 - (800 + 100) = 200
  $expectedProfitPct = $cotizacion->obtener_re_quote_profit_percentage(); // 200/1100*100 = 18.18%

  check('sanity: expected Total Price is $1100', 1100.00, $expectedTotalPrice);
  check('sanity: expected Total Profit is $200', 200.00, $expectedTotalProfit);

  $html = renderReQuotePdf($root, $cotizacion, $re_quote, [], [], $total_services, [], $usuario_designado);

  check('PDF includes a Report Total section', true, strpos($html, 'Report Total') !== false || strpos($html, 'TOTAL PRICE') !== false);
  check('PDF shows combined Total Price matching the bottom bar', true, strpos($html, '$ ' . number_format($expectedTotalPrice, 2)) !== false);
  check('PDF shows combined Total Profit matching the bottom bar', true, strpos($html, '$ ' . number_format($expectedTotalProfit, 2)) !== false);
  check('PDF shows combined Profit % matching the bottom bar', true, strpos($html, number_format($expectedProfitPct, 2) . '%') !== false);

} finally {
  $c->rollBack();
  Conexion::cerrar_conexion();
}

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
