<?php
/**
 * Regression test: Search Quotes / Partial Invoices with a term holding
 * non-latin1 punctuation (U+2011 NON-BREAKING HYPHEN pasted from a PDF).
 *
 * subitems.brand/part_number* and invoices.name are latin1 columns; a utf8mb4
 * term with a character outside latin1 used to fail the LIKE with MySQL 1267
 * (printed as HTML in front of the JSON, or swallowed into "No matching records").
 *
 * Inserts a quote + item + subitem + invoice stored with ASCII hyphens, searches
 * with the U+2011 term through basic, advanced and invoices, then ROLLS BACK.
 *
 * Run:  docker exec lamp-php84 php /var/www/html/rfq/tests/php/search_unicode_term_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Quote/Rfq.inc.php';
require_once $root . 'app/Quote/RepositorioRfq.inc.php';
require_once $root . 'app/Report/PipelineMetricsRepository.inc.php';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  if ($expected === $actual) { $pass++; echo "  PASS  $label\n"; }
  else { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

/** Runs $fn, returning [result, printed output, exception message|null]. */
function run(callable $fn) {
  ob_start();
  $result = null; $error = null;
  try { $result = $fn(); } catch (Throwable $e) { $error = $e->getMessage(); }
  $out = ob_get_clean();
  return [$result, $out, $error];
}

$NBH = "\u{2011}"; // NON-BREAKING HYPHEN, bytes e2 80 91
$EMOJI = "\u{1F600}";
$U = strtoupper(substr(md5(uniqid('', true)), 0, 8));
$MARK = 'UNITESTCLIENT' . $U;

$quoteCode   = "IFB TC-12-09-26A-$U";
$subPart     = "SUBPN-77-$U";
$invoiceName = "INV TC-12-09-26A-$U";

$filters = [
  'statuses' => [], 'user' => '', 'bid_type' => '', 'contract_type' => '',
  'date_field' => 'created', 'date_from' => '', 'date_to' => '',
  'price_min' => '', 'price_max' => '', 'client' => $MARK, 'state' => '',
];

$conexion = Conexion::obtener_conexion();
$conexion->beginTransaction();
try {
  $stmt = $conexion->prepare("INSERT INTO rfq (id_usuario, usuario_designado, canal, email_code, type_of_bid,
      issue_date, end_date, status, completado, comments, award, payment_terms, address, ship_to, ship_via,
      taxes, profit, additional, shipping_cost, shipping, fullfillment, contract_number, sources_sought,
      total_price, invoice, submitted_invoice, deleted, name, client, state, type_of_contract, created_at)
    VALUES (1, 1, 'UNITEST', :code, 'IT', '03/15/2099', '03/15/2099', 0, 0, 'No comments', 0, 'Net 30', '', '', '',
      0, 0, '', 0, '', 0, '', 0, 0, 0, 0, 0, 'Unicode Search Test', :client, 'FL', 'Purchase Order', '2099-03-15 00:00:00')");
  $stmt->execute([':code' => $quoteCode, ':client' => $MARK]);
  $idRfq = (int)$conexion->lastInsertId();

  $stmt = $conexion->prepare("INSERT INTO item (id_rfq, id_usuario, provider_menor, brand, brand_project, part_number,
      part_number_project, description, description_project, quantity, unit_price, total_price, comments, website, additional)
    VALUES (:r, 1, 0, 'B', 'B', 'P', 'P', 'unitest item', 'unitest item', 1, 0, 0, '', '', '')");
  $stmt->execute([':r' => $idRfq]);
  $idItem = (int)$conexion->lastInsertId();

  $stmt = $conexion->prepare("INSERT INTO subitems (id_item, provider_menor, brand, brand_project, part_number,
      part_number_project, description, description_project, quantity, unit_price, total_price, comments, website, additional)
    VALUES (:i, 0, 'B', 'B', :pn, :pn2, 'unitest sub', 'unitest sub', 1, 0, 0, '', '', '')");
  $stmt->execute([':i' => $idItem, ':pn' => $subPart, ':pn2' => $subPart]);

  $stmt = $conexion->prepare("INSERT INTO invoices (id_rfq, name, created_at) VALUES (:r, :n, '2099-03-15')");
  $stmt->execute([':r' => $idRfq, ':n' => $invoiceName]);

  $pastedQuote   = str_replace('-', $NBH, $quoteCode);
  $pastedSub     = str_replace('-', $NBH, $subPart);
  $pastedInvoice = str_replace('-', $NBH, $invoiceName);

  echo "[normalizeSearchTerm]\n";
  check('hyphen/dash variants → "-"', '-------', RepositorioRfq::normalizeSearchTerm("\u{2010}\u{2011}\u{2012}\u{2013}\u{2212}\u{FE63}\u{FF0D}"));
  check('em dash / horizontal bar / small em dash → "-"', '---', RepositorioRfq::normalizeSearchTerm("\u{2014}\u{2015}\u{FE58}"));
  check('NBSP / figure / narrow NBSP → space', 'a b c d', RepositorioRfq::normalizeSearchTerm("a\u{00A0}b\u{2007}c\u{202F}d"));
  check('curly quotes → straight', "'x' \"y\"", RepositorioRfq::normalizeSearchTerm("\u{2018}x\u{2019} \u{201C}y\u{201D}"));
  check('pasted term → ASCII', $quoteCode, RepositorioRfq::normalizeSearchTerm($pastedQuote));
  check('accents and emoji untouched', "é $EMOJI", RepositorioRfq::normalizeSearchTerm("é $EMOJI"));

  echo "[Basic search — U+2011 term finds ASCII-hyphen quote]\n";
  [$rows, $out, $err] = run(fn() => RepositorioRfq::getSearchedQuotesByChannel($conexion, 0, 10, '', 0, 'desc', $pastedQuote));
  check('list: no exception', null, $err);
  check('list: nothing printed', '', $out);
  check('list: quote found', [$idRfq], array_map('intval', array_column((array)$rows, 'id')));
  [$count, $out, $err] = run(fn() => RepositorioRfq::getTotalSearchedQuotesByChannelCount($conexion, $pastedQuote));
  check('count: no exception', null, $err);
  check('count: nothing printed', '', $out);
  check('count: 1', 1, (int)$count);

  echo "[Basic search — subitem part number]\n";
  [$rows, $out, $err] = run(fn() => RepositorioRfq::getSearchedQuotesByChannel($conexion, 0, 10, '', 0, 'desc', $pastedSub));
  check('subitem: no exception', null, $err);
  check('subitem: quote found', [$idRfq], array_map('intval', array_column((array)$rows, 'id')));
  [$count, , ] = run(fn() => RepositorioRfq::getTotalSearchedQuotesByChannelCount($conexion, $pastedSub));
  check('subitem count: 1', 1, (int)$count);

  echo "[Advanced search — U+2011 term]\n";
  [$rows, $out, $err] = run(fn() => RepositorioRfq::getAdvancedSearchedQuotes($conexion, 0, 10, 0, 'desc', $pastedQuote, $filters));
  check('list: no exception', null, $err);
  check('list: nothing printed', '', $out);
  check('list: quote found', [$idRfq], array_map('intval', array_column((array)$rows, 'id')));
  [$count, $out, $err] = run(fn() => RepositorioRfq::getAdvancedSearchedQuotesCount($conexion, $pastedQuote, $filters));
  check('count: no exception', null, $err);
  check('count: 1', 1, (int)$count);
  [$count, , $err] = run(fn() => RepositorioRfq::getAdvancedSearchedQuotesCount($conexion, $pastedSub, $filters));
  check('subitem count: no exception', null, $err);
  check('subitem count: 1', 1, (int)$count);

  echo "[Invoices search — U+2011 term]\n";
  [$rows, $out, $err] = run(fn() => RepositorioRfq::getSearchedInvoices($conexion, 0, 10, 1, 'desc', $pastedInvoice));
  check('list: no exception', null, $err);
  check('list: nothing printed', '', $out);
  check('list: invoice found', [$invoiceName], array_column((array)$rows, 'invoice_name'));
  [$count, $out, $err] = run(fn() => RepositorioRfq::getTotalSearchedInvoicesCount($conexion, $pastedInvoice));
  check('count: no exception', null, $err);
  check('count: nothing printed', '', $out);
  check('count: 1', 1, (int)$count);

  // CJK is not latin1 and not normalized, so it only passes through CONVERT(... USING utf8mb4);
  // the emoji is spec-required (MySQL happens to coerce 4-byte characters without 1267).
  foreach (["emoji" => "$U $EMOJI", "CJK" => "$U \u{4E2D}"] as $kind => $term) {
    echo "[$kind term — empty result, no error]\n";
    [$rows, $out, $err] = run(fn() => RepositorioRfq::getSearchedQuotesByChannel($conexion, 0, 10, '', 0, 'desc', $term));
    check('basic list: no exception', null, $err);
    check('basic list: nothing printed', '', $out);
    check('basic list: empty', [], $rows);
    [$count, , $err] = run(fn() => RepositorioRfq::getTotalSearchedQuotesByChannelCount($conexion, $term));
    check('basic count: no exception', null, $err);
    check('basic count: 0', 0, (int)$count);
    [$rows, , $err] = run(fn() => RepositorioRfq::getAdvancedSearchedQuotes($conexion, 0, 10, 0, 'desc', $term, $filters));
    check('advanced list: no exception', null, $err);
    check('advanced list: empty', [], $rows);
    [$count, , $err] = run(fn() => RepositorioRfq::getAdvancedSearchedQuotesCount($conexion, $term, $filters));
    check('advanced count: no exception', null, $err);
    check('advanced count: 0', 0, (int)$count);
    [$rows, , $err] = run(fn() => RepositorioRfq::getSearchedInvoices($conexion, 0, 10, 1, 'desc', $term));
    check('invoices list: no exception', null, $err);
    check('invoices list: empty', [], $rows);
    [$count, , $err] = run(fn() => RepositorioRfq::getTotalSearchedInvoicesCount($conexion, $term));
    check('invoices count: no exception', null, $err);
    check('invoices count: 0', 0, (int)$count);
  }

  echo "[Query failure surfaces as an exception, not printed HTML]\n";
  // A bogus sort direction makes the SQL invalid; the endpoint's catch must see it.
  [, $out, $err] = run(fn() => RepositorioRfq::getSearchedInvoices($conexion, 0, 10, 1, 'nonsense', 'x'));
  check('invoices: throws', true, $err !== null);
  check('invoices: nothing printed', '', $out);
  [, $out, $err] = run(fn() => RepositorioRfq::getSearchedQuotesByChannel($conexion, 0, 10, '', 0, 'nonsense', 'x'));
  check('basic: throws', true, $err !== null);
  check('basic: nothing printed', '', $out);
} finally {
  if ($conexion->inTransaction()) $conexion->rollBack();
}

echo "\n$pass passed, $fail failed\n";
exit($fail ? 1 : 0);
