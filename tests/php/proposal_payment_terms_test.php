<?php
/**
 * Unit test for ProposalRepository::resolve_payment_terms().
 *
 * Bug: the Proposal PDF always used services_payment_term for a services-type
 * quote, even when the quote has zero service line items — it should fall back
 * to the item-level payment_terms in that case, same as a non-services quote.
 *
 * Run:  docker exec lamp-php83 php /var/www/html/rfq/tests/php/proposal_payment_terms_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Utilities/ProposalRepository.inc.php';
require_once $root . 'app/Quote/Rfq.inc.php';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = $expected === $actual;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

/* ---------- services quote with service items: unchanged, uses services_payment_term ---------- */
$services_with_items = new Rfq([
  'type_of_bid' => 'Services',
  'payment_terms' => 'Net 30',
  'services_payment_term' => 'Net 30/CC',
]);
check(
  'services quote with items uses services_payment_term',
  'Net 30/CC',
  ProposalRepository::resolve_payment_terms($services_with_items, [['id' => 1]])
);

/* ---------- services quote with zero service items: falls back to payment_terms ---------- */
$services_no_items = new Rfq([
  'type_of_bid' => 'Services',
  'payment_terms' => 'Net 30',
  'services_payment_term' => 'Net 30/CC',
]);
check(
  'services quote with zero items falls back to payment_terms',
  'Net 30',
  ProposalRepository::resolve_payment_terms($services_no_items, [])
);

/* ---------- non-services quote: unchanged, always uses payment_terms ---------- */
$non_services = new Rfq([
  'type_of_bid' => 'Cameras',
  'payment_terms' => 'Net 30',
  'services_payment_term' => 'Net 30/CC',
]);
check(
  'non-services quote uses payment_terms regardless of $services',
  'Net 30',
  ProposalRepository::resolve_payment_terms($non_services, [])
);

/* ---------- services quote, items present, but services_payment_term NULL in DB ---------- */
$services_null_term = new Rfq([
  'type_of_bid' => 'Services',
  'payment_terms' => 'Net 30',
  'services_payment_term' => null,
]);
check(
  'services quote with items but NULL services_payment_term falls back to payment_terms',
  'Net 30',
  ProposalRepository::resolve_payment_terms($services_null_term, [['id' => 1]])
);

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
