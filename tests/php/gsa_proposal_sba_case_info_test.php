<?php
/**
 * Bug: the GSA proposal PDF ($encabezado = 1) still printed the SBA 8(a) Case No
 * and Entrance/Exit dates in its first highlighted row. Only the "Open Market
 * Pricing Proposal" title and the certification line should remain.
 *
 * Renders herramientas/pdfTemplates/proposal.inc.php to HTML (no mPDF) for both
 * the GSA and the regular proposal.
 *
 * Run:  docker exec lamp-php84 php /var/www/html/rfq/tests/php/gsa_proposal_sba_case_info_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Utilities/ProposalRepository.inc.php';
require_once $root . 'app/Quote/Rfq.inc.php';

$pass = 0; $fail = 0;
function check($label, $ok) {
  global $pass, $fail;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label\n"; }
}

function render_proposal($encabezado) {
  $cotizacion = new Rfq(['id' => 1, 'type_of_bid' => 'Cameras', 'payment_terms' => 'Net 30', 'total_price' => 0, 'shipping_cost' => 0]);
  $usuario_designado = new class {
    public function __call($name, $args) { return ''; }
  };
  $items = []; $services = []; $total_service = 0;
  $payment_terms = 'Net 30';
  $logo = 'logo_proposal.png';
  $fecha_completado = $expiration_date = '01/01/2026';
  ob_start();
  include __DIR__ . '/../../herramientas/pdfTemplates/proposal.inc.php';
  return ob_get_clean();
}

$gsa = render_proposal(1);
$regular = render_proposal(0);

echo "GSA proposal (encabezado = 1):\n";
check('keeps "Open Market Pricing Proposal"', strpos($gsa, 'Open Market Pricing Proposal') !== false);
check('keeps the SBA 8(a) and HUBZone certification line', strpos($gsa, 'E-Logic is an SBA 8(a) and HUBZone Certified SB') !== false);
check('does not print the SBA 8(a) Case No', strpos($gsa, 'Case No') === false && strpos($gsa, 'C0069X') === false);
check('does not print the Entrance / Exit dates', strpos($gsa, 'Entrance:') === false && strpos($gsa, 'Exit:') === false);
check('certification line is the last line of the cell (no trailing <br>)',
  (bool) preg_match('#HUBZone Certified SB\s*</td>#', $gsa));

echo "Regular proposal (encabezado = 0):\n";
check('has no SBA row at all', strpos($regular, 'class="sba-row"') === false && strpos($regular, 'Open Market Pricing Proposal') === false);

echo "\n$pass passed, $fail failed\n";
exit($fail ? 1 : 0);
