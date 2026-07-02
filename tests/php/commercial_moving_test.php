<?php
/**
 * Integration test for the Commercial Moving bid type + 50/50 payment term.
 *
 * Covers the server-side pieces of the feature:
 *   • ProposalRepository::split_5050 reconciles to the exact total (odd cents / zero)
 *   • ProposalRepository::is_split_term recognises only the canonical split string
 *   • print_service treats the 50/50 term as a ×1 payment schedule (no uplift) —
 *     identical output to Net 30, unlike Net 30/CC (×1.03)
 *   • the "Commercial Moving" bid type surfaces via TypeOfBidRepository::get_all
 *     once the migration is applied (self-cleaning — removes a row it inserts)
 *
 * Run:  docker exec lamp-php84 php /var/www/html/rfq/tests/php/commercial_moving_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Service/Service.inc.php';
require_once $root . 'app/Utilities/ProposalRepository.inc.php';
require_once $root . 'app/TypeOfBid/TypeOfBid.inc.php';
require_once $root . 'app/TypeOfBid/TypeOfBidRepository.inc.php';

$SPLIT = '50% Upfront / 50% on Completion';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = $expected === $actual;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

/* ---------- split_5050 reconciliation ---------- */
$clean = ProposalRepository::split_5050(25000);
check('split 25000 upfront',    12500.0, (float) $clean['upfront']);
check('split 25000 completion', 12500.0, (float) $clean['completion']);

$odd = ProposalRepository::split_5050(8749.55);
check('split odd upfront (extra cent)', 4374.78, (float) $odd['upfront']);
check('split odd completion',           4374.77, (float) $odd['completion']);
check('split odd reconciles to exact total', 874955, (int) round(($odd['upfront'] + $odd['completion']) * 100));

$zero = ProposalRepository::split_5050(0);
check('split zero upfront',    0.0, (float) $zero['upfront']);
check('split zero completion', 0.0, (float) $zero['completion']);

/* ---------- is_split_term ---------- */
check('is_split_term(split)',      true,  ProposalRepository::is_split_term($SPLIT));
check('is_split_term(Net 30)',     false, ProposalRepository::is_split_term('Net 30'));
check('is_split_term(Net 30/CC)',  false, ProposalRepository::is_split_term('Net 30/CC'));

/* ---------- print_service treats 50/50 as ×1 (no uplift) ---------- */
$service = new Service(1, 1, 'On-site AV integration labor', 80, 150.00, 12000.00, 0, null);
$split_html  = ProposalRepository::print_service($SPLIT, $service, 1);
$net30_html  = ProposalRepository::print_service('Net 30', $service, 1);
$cc_html     = ProposalRepository::print_service('Net 30/CC', $service, 1);
check('50/50 service output equals Net 30 (×1, no uplift)', $net30_html, $split_html);
check('50/50 service keeps base unit price $150.00', true, strpos($split_html, '$ 150.00') !== false);
check('Net 30/CC service applies ×1.03 uplift ($154.50)', true, strpos($cc_html, '$ 154.50') !== false);

/* ---------- Commercial Moving bid type surfaces in the dropdown ---------- */
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

$has = function () use ($conexion) {
  foreach (TypeOfBidRepository::get_all($conexion) as $t) {
    if ($t->get_type_of_bid() === 'Commercial Moving') return true;
  }
  return false;
};

$already = $has();
if (!$already) {
  // Apply the migration insert, then clean up the row we added.
  $conexion->exec(file_get_contents($root . 'sql/commercial_moving_payment_term_migration.sql'));
}
check('Commercial Moving present in TypeOfBidRepository::get_all', true, $has());
if (!$already) {
  $conexion->exec("DELETE FROM type_of_bids WHERE type_of_bid = 'Commercial Moving'");
}
Conexion::cerrar_conexion();

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
