<?php
/**
 * Integration test for: Actions button undefined-variable warning
 * (bugs/actions-button-undefined-variable.md).
 *
 * forms/quote/templates/actions_button.inc.php reads $re_quote_exists without
 * defining it — it used to rely on a sibling template (downloads_button.inc.php)
 * setting that variable earlier in the include order, but downloads_button.inc.php
 * is now included AFTER actions_button.inc.php, so the variable is undefined by
 * the time actions_button.inc.php needs it. Covers:
 *   • rendering the template throws no "Undefined variable $re_quote_exists" warning
 *   • Tracking link shows when the quote is in Fulfillment and a re-quote exists
 *   • Tracking link stays hidden when the quote is in Fulfillment but no re-quote exists
 *
 * Transaction-isolated (ROLLBACK), same pattern as checklist_info_drawer_test.php.
 * Run:  docker exec lamp-php84 php /var/www/html/rfq/tests/php/actions_button_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/routes.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Quote/Rfq.inc.php';
require_once $root . 'app/ReQuote/ReQuote.inc.php';
require_once $root . 'app/ReQuote/ReQuoteRepository.inc.php';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = $expected === $actual;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

function insertQuote($conexion, array $o = []) {
  $f = array_merge([
    'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'ABTEST',
    'email_code' => 'AB-' . uniqid(), 'type_of_bid' => 'IT',
    'issue_date' => '01/01/1999', 'end_date' => '01/01/1999', 'status' => 0, 'completado' => 0,
    'comments' => 'No comments', 'award' => 0, 'payment_terms' => 'Net 30',
    'address' => '', 'ship_to' => '', 'ship_via' => '', 'taxes' => 0, 'profit' => 0,
    'additional' => '', 'shipping_cost' => 0, 'shipping' => '', 'fullfillment' => 0,
    'contract_number' => '', 'total_price' => 0,
    'invoice' => 0, 'submitted_invoice' => 0, 'deleted' => 0, 'name' => 'Actions Button Test',
  ], $o);
  $cols = array_keys($f);
  $ph = array_map(fn($c) => ':' . $c, $cols);
  $sql = 'INSERT INTO rfq (' . implode(',', $cols) . ') VALUES (' . implode(',', $ph) . ')';
  $stmt = $conexion->prepare($sql);
  foreach ($f as $c => $v) { $stmt->bindValue(':' . $c, $v); }
  $stmt->execute();
  return (int)$conexion->lastInsertId();
}

function insertReQuote($conexion, $id_rfq) {
  $sql = 'INSERT INTO re_quotes (id_rfq, total_cost, total_price, payment_terms, taxes, profit, shipping_cost, shipping)
          VALUES (:id_rfq, 0, 0, "Net 30", 0, 0, 0, "")';
  $stmt = $conexion->prepare($sql);
  $stmt->bindValue(':id_rfq', $id_rfq, PDO::PARAM_INT);
  $stmt->execute();
}

/** Renders the template and returns [html, warnings] — warnings are PHP E_WARNING/E_NOTICE messages raised during render. */
function renderActionsButton($root, $id_rfq, Rfq $cotizacion_recuperada) {
  $warnings = [];
  set_error_handler(function ($errno, $errstr) use (&$warnings) {
    $warnings[] = $errstr;
    return true;
  }, E_WARNING | E_NOTICE);

  ob_start();
  include $root . 'forms/quote/templates/actions_button.inc.php';
  $html = ob_get_clean();

  restore_error_handler();
  return [$html, $warnings];
}

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$conexion->beginTransaction();

try {
  // Fulfillment quote WITH a re-quote — Tracking link should show, no warnings.
  $idWithReQuote = insertQuote($conexion, ['fullfillment' => 1]);
  insertReQuote($conexion, $idWithReQuote);
  $quoteWithReQuote = new Rfq(['id' => $idWithReQuote, 'canal' => 'ABTEST', 'award' => 0, 'fullfillment' => 1]);

  [$html, $warnings] = renderActionsButton($root, $idWithReQuote, $quoteWithReQuote);
  check('no PHP warning rendering actions_button.inc.php', [], $warnings);
  check('Tracking link shown for Fulfillment quote with a re-quote', true, strpos($html, 'Tracking') !== false && strpos($html, TRACKING . $idWithReQuote) !== false);

  // Fulfillment quote WITHOUT a re-quote — Tracking link must stay hidden, still no warnings.
  $idNoReQuote = insertQuote($conexion, ['fullfillment' => 1]);
  $quoteNoReQuote = new Rfq(['id' => $idNoReQuote, 'canal' => 'ABTEST', 'award' => 0, 'fullfillment' => 1]);

  [$html2, $warnings2] = renderActionsButton($root, $idNoReQuote, $quoteNoReQuote);
  check('no PHP warning rendering actions_button.inc.php (no re-quote)', [], $warnings2);
  check('Tracking link hidden for Fulfillment quote without a re-quote', false, strpos($html2, 'Tracking') !== false);

} finally {
  $conexion->rollBack();
  Conexion::cerrar_conexion();
}

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
