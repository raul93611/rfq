<?php
/**
 * Integration test for the Shared Notification Mailbox.
 *
 * Covers NotificationMailboxRepository's single-row store:
 *   • save() upserts the one connection, get() reads it back
 *   • isConnected() reflects presence of a refresh token
 *   • getAccessToken() returns a still-valid stored token without a network call
 *   • clear() removes the connection
 *
 * Transaction-isolated: everything runs inside a ROLLBACK so no real row survives.
 * Run:  docker exec lamp-php84 php /var/www/html/rfq/tests/php/shared_mailbox_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Setting/NotificationMailboxRepository.inc.php';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = $expected === $actual;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$conexion->beginTransaction();

try {
  // Start clean within the transaction
  NotificationMailboxRepository::clear($conexion);
  check('not connected initially', false, NotificationMailboxRepository::isConnected($conexion));
  check('get() is null initially', null, NotificationMailboxRepository::get($conexion));

  // Save a connection with a far-future expiry
  $future = time() + 3600;
  NotificationMailboxRepository::save($conexion, 'ACCESS-1', 'REFRESH-1', $future, 'portal@e-logic.us', 7);

  $row = NotificationMailboxRepository::get($conexion);
  check('get() returns a row after save', true, is_array($row));
  check('stored email',        'portal@e-logic.us', $row['ms_email']);
  check('stored access token', 'ACCESS-1',          $row['ms_access_token']);
  check('stored connected_by', 7,                   (int)$row['connected_by']);
  check('isConnected after save', true, NotificationMailboxRepository::isConnected($conexion));

  // A still-valid token is returned directly (no network refresh)
  check('getAccessToken returns stored token', 'ACCESS-1', NotificationMailboxRepository::getAccessToken($conexion));

  // Re-save (upsert) keeps a single row and overwrites fields
  NotificationMailboxRepository::save($conexion, 'ACCESS-2', 'REFRESH-2', $future, 'bids@e-logic.us', 9);
  $count = (int)$conexion->query('SELECT COUNT(*) FROM notification_mailbox')->fetchColumn();
  check('upsert keeps exactly one row', 1, $count);
  check('upsert overwrote email', 'bids@e-logic.us', NotificationMailboxRepository::get($conexion)['ms_email']);

  // Clear removes it
  NotificationMailboxRepository::clear($conexion);
  check('not connected after clear', false, NotificationMailboxRepository::isConnected($conexion));
  check('getAccessToken null after clear', null, NotificationMailboxRepository::getAccessToken($conexion));

} finally {
  $conexion->rollBack();
  Conexion::cerrar_conexion();
}

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
