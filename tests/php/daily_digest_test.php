<?php
/**
 * Integration test for the Daily RFQ Digest Email (DigestRepository + DigestEmailTemplate +
 * RepositorioUsuario::getActiveAdminUsers + NotificationEmail::sendCustom).
 *
 * Covers:
 *   - Created/Submitted/Awarded/Due list queries scope to the right date column and
 *     exclude deleted quotes and off-date quotes.
 *   - digest_send_log dedup: hasSentOn/markSent, and markSent is idempotent (unique date).
 *   - getActiveAdminUsers only returns active users whose cargo includes the Admin role.
 *   - Email template renders all four sections, quote links, and per-section empty states.
 *   - NotificationEmail::sendCustom is a safe no-op with no shared mailbox connected.
 *
 * Uses a far-future sentinel date so it can never collide with real digest_send_log rows.
 * Transaction-isolated: everything runs inside a ROLLBACK so no real row survives.
 * Run:  docker exec lamp-php84 php /var/www/html/rfq/tests/php/daily_digest_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/routes.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Report/DigestRepository.inc.php';
require_once $root . 'app/User/Usuario.inc.php';
require_once $root . 'app/User/RepositorioUsuario.inc.php';
require_once $root . 'app/Setting/NotificationMailboxRepository.inc.php';
require_once $root . 'app/Utilities/NotificationEmail.inc.php';
require_once $root . 'app/Utilities/DigestEmailTemplate.inc.php';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = $expected === $actual;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

/** Insert one rfq row. $o overrides the defaults (all NOT-NULL columns are covered). */
function insertQuote($conexion, array $o = []) {
  $f = array_merge([
    'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'FedBid',
    'email_code' => 'DIGESTTEST-' . uniqid(), 'type_of_bid' => 'IT',
    'issue_date' => '01/01/1999', 'end_date' => '01/01/1999', 'status' => 0, 'completado' => 0,
    'comments' => 'No comments', 'award' => 0, 'payment_terms' => 'Net 30',
    'address' => '', 'ship_to' => '', 'ship_via' => '', 'taxes' => 0, 'profit' => 0,
    'additional' => '', 'shipping_cost' => 0, 'shipping' => '', 'fullfillment' => 0,
    'contract_number' => '', 'sources_sought' => 0, 'total_price' => 0,
    'invoice' => 0, 'submitted_invoice' => 0, 'deleted' => 0,
    'name' => 'Digest Test Quote', 'client' => 'Test Client',
  ], $o);
  $cols = array_keys($f);
  $ph = array_map(fn($c) => ':' . $c, $cols);
  $sql = 'INSERT INTO rfq (' . implode(',', $cols) . ') VALUES (' . implode(',', $ph) . ')';
  $stmt = $conexion->prepare($sql);
  foreach ($f as $c => $v) { $stmt->bindValue(':' . $c, $v); }
  $stmt->execute();
  return (int)$conexion->lastInsertId();
}

$conexion = Conexion::obtener_conexion();
$conexion->beginTransaction();

try {
  $sentinel_yesterday = '2098-04-10';
  $sentinel_today      = '2098-04-11';
  $off_date            = '2098-04-01';

  // --- Created ---
  $created_id = insertQuote($conexion, ['created_at' => $sentinel_yesterday, 'client' => 'Wake County PS', 'canal' => 'FedBid', 'name' => 'Classroom AV Modernization']);
  insertQuote($conexion, ['created_at' => $sentinel_yesterday, 'deleted' => 1]); // excluded: deleted
  insertQuote($conexion, ['created_at' => $off_date]); // excluded: wrong date

  // --- Submitted ---
  $submitted_id = insertQuote($conexion, ['fecha_submitted' => $sentinel_yesterday, 'client' => 'Houston ISD', 'canal' => 'FBO', 'name' => 'PA System Replacement']);
  insertQuote($conexion, ['fecha_submitted' => $sentinel_yesterday, 'deleted' => 1]);

  // --- Awarded ---
  $awarded_id = insertQuote($conexion, ['fecha_award' => $sentinel_yesterday, 'award' => 1, 'client' => 'Maricopa County', 'name' => 'Surveillance Expansion']);
  insertQuote($conexion, ['fecha_award' => $sentinel_yesterday, 'award' => 1, 'deleted' => 1]);

  // --- Due today: none inserted, exercises the empty-state path ---

  echo "[Created]\n";
  $created_rows = DigestRepository::getCreatedOn($conexion, $sentinel_yesterday);
  check('created excludes deleted and off-date rows', 1, count($created_rows));
  check('created row is the right quote', $created_id, (int)$created_rows[0]['id']);
  check('created row carries channel translated later by template (raw canal stored)', 'FedBid', $created_rows[0]['canal']);

  echo "[Submitted]\n";
  $submitted_rows = DigestRepository::getSubmittedOn($conexion, $sentinel_yesterday);
  check('submitted excludes deleted', 1, count($submitted_rows));
  check('submitted row is the right quote', $submitted_id, (int)$submitted_rows[0]['id']);

  echo "[Awarded]\n";
  $awarded_rows = DigestRepository::getAwardedOn($conexion, $sentinel_yesterday);
  check('awarded excludes deleted', 1, count($awarded_rows));
  check('awarded row is the right quote', $awarded_id, (int)$awarded_rows[0]['id']);

  echo "[Due today — empty]\n";
  $due_rows = DigestRepository::getDueOn($conexion, $sentinel_today);
  check('due today is empty', 0, count($due_rows));

  echo "[Dedup log]\n";
  check('not sent yet for sentinel date', false, DigestRepository::hasSentOn($conexion, $sentinel_today));
  DigestRepository::markSent($conexion, $sentinel_today);
  check('sent after markSent', true, DigestRepository::hasSentOn($conexion, $sentinel_today));
  DigestRepository::markSent($conexion, $sentinel_today); // second call must not throw / must not duplicate
  $log_count = (int)$conexion->query("SELECT COUNT(*) FROM digest_send_log WHERE digest_date = '{$sentinel_today}'")->fetchColumn();
  check('markSent is idempotent (one row per date)', 1, $log_count);

  echo "[Active admin users]\n";
  $admin_username = 'digesttest_admin_' . uniqid();
  $nonadmin_username = 'digesttest_nonadmin_' . uniqid();
  $conexion->prepare('INSERT INTO usuarios (nombre_usuario, password, nombres, apellidos, cargo, email, status, hash_recover_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)')
    ->execute([$admin_username, 'x', 'Ada', 'Admin', '1', 'ada@example.com', 1, '']);
  $test_admin_id = (int)$conexion->lastInsertId();
  $conexion->prepare('INSERT INTO usuarios (nombre_usuario, password, nombres, apellidos, cargo, email, status, hash_recover_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)')
    ->execute([$nonadmin_username, 'x', 'Rita', 'Rfq', '3', 'rita@example.com', 1, '']);
  $conexion->prepare('INSERT INTO usuarios (nombre_usuario, password, nombres, apellidos, cargo, email, status, hash_recover_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)')
    ->execute([$admin_username . '_disabled', 'x', 'Dan', 'Disabled', '1', 'dan@example.com', 0, '']);

  $admins = RepositorioUsuario::getActiveAdminUsers($conexion);
  $admin_ids = array_map(fn($u) => (int)$u->obtener_id(), $admins);
  check('active admin user is included', true, in_array($test_admin_id, $admin_ids, true));
  $found_nonadmin = false;
  $found_disabled = false;
  foreach ($admins as $u) {
    if ($u->obtener_nombre_usuario() === $nonadmin_username) $found_nonadmin = true;
    if ($u->obtener_nombre_usuario() === $admin_username . '_disabled') $found_disabled = true;
  }
  check('non-admin user excluded', false, $found_nonadmin);
  check('disabled admin user excluded', false, $found_disabled);

  echo "[Email template]\n";
  $html = DigestEmailTemplate::render('Friday, April 10, 2098', $created_rows, $submitted_rows, $awarded_rows, $due_rows);
  check('renders Created section title', true, str_contains($html, 'Created'));
  check('renders Submitted section title', true, str_contains($html, 'Submitted'));
  check('renders Awarded section title', true, str_contains($html, 'Awarded'));
  check('renders Due Today section title', true, str_contains($html, 'Due Today'));
  check('renders created quote name', true, str_contains($html, 'Classroom AV Modernization'));
  check('renders created quote link to the quote page', true, str_contains($html, EDITAR_COTIZACION . '/' . $created_id));
  check('translates FedBid channel to Unison', true, str_contains($html, 'Unison'));
  check('translates FBO channel to SAM', true, str_contains($html, 'SAM'));
  check('renders empty state for Due Today', true, str_contains($html, 'No quotes due today.'));
  check('does not render empty state for a populated section', false, str_contains($html, 'No quotes created yesterday.'));

  echo "[Safe no-op when mailbox disconnected]\n";
  NotificationMailboxRepository::clear($conexion);
  $threw = false;
  try {
    NotificationEmail::sendCustom($conexion, 'admin@example.com', 'Test subject', '<p>test</p>');
  } catch (Throwable $e) {
    $threw = true;
  }
  check('sendCustom does not throw when mailbox is disconnected', false, $threw);

} finally {
  $conexion->rollBack();
}

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
