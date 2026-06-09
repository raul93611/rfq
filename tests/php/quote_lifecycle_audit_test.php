<?php
/**
 * Integration test for the Quote Lifecycle audit helpers on AuditTrailRepository:
 *   - quote_created_audit_trail  -> action_type 'quote_created' (Status group, green)
 *   - sync_to_sheet_audit_trail  -> action_type 'sync_to_sheet' (Sync group, "Synced")
 *   - break_sync_audit_trail     -> action_type 'break_sync'    (Sync group, "Unsynced")
 *
 * Each helper mirrors quote_status_audit_trail: it derives username/id from
 * $_SESSION['user] and writes one row through insert_audit_trail. The test stubs
 * the session user, inserts a quote, calls each helper, reads back the rows, then
 * ROLLS BACK so nothing persists.
 *
 * Run:  docker exec lamp-php83 php /var/www/html/rfq/tests/php/quote_lifecycle_audit_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Quote/AuditTrail.inc.php';
require_once $root . 'app/Comment/RepositorioComment.inc.php';
require_once $root . 'app/Quote/AuditTrailRepository.inc.php';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = $expected === $actual;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

/** Minimal stand-in for the logged-in user the helpers read from the session. */
class FakeSessionUser {
  private $id; private $name;
  public function __construct($id, $name) { $this->id = $id; $this->name = $name; }
  public function obtener_id() { return $this->id; }
  public function obtener_nombre_usuario() { return $this->name; }
}
$_SESSION['user'] = new FakeSessionUser(7, 'RSantos');

$conexion = Conexion::obtener_conexion();

function insertQuote($conexion, array $o = []) {
  $f = array_merge([
    'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'AUDITTEST',
    'email_code' => 'AUDIT-' . uniqid(), 'type_of_bid' => 'IT',
    'issue_date' => '01/01/1999', 'end_date' => '01/01/1999', 'status' => 0, 'completado' => 0,
    'comments' => 'No comments', 'award' => 0, 'payment_terms' => 'Net 30',
    'address' => '', 'ship_to' => '', 'ship_via' => '', 'taxes' => 0, 'profit' => 0,
    'additional' => '', 'shipping_cost' => 0, 'shipping' => '', 'fullfillment' => 0,
    'contract_number' => '', 'total_price' => 0,
    'invoice' => 0, 'submitted_invoice' => 0, 'deleted' => 0, 'name' => 'Audit Test',
  ], $o);
  $cols = array_keys($f);
  $ph = array_map(fn($c) => ':' . $c, $cols);
  $sql = 'INSERT INTO rfq (' . implode(',', $cols) . ') VALUES (' . implode(',', $ph) . ')';
  $stmt = $conexion->prepare($sql);
  foreach ($f as $c => $v) { $stmt->bindValue(':' . $c, $v); }
  $stmt->execute();
  return (int)$conexion->lastInsertId();
}

/** Read back the audit rows for a quote, newest first. */
function readAudit($conexion, $id_rfq) {
  $stmt = $conexion->prepare('SELECT username, action_type, id_user, audit_trail FROM audit_trails WHERE id_rfq = :id ORDER BY id DESC');
  $stmt->bindValue(':id', $id_rfq, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$conexion->beginTransaction();
try {
  $id_rfq = insertQuote($conexion);

  echo "[quote_created]\n";
  AuditTrailRepository::quote_created_audit_trail($conexion, $id_rfq);
  $rows = readAudit($conexion, $id_rfq);
  check('one row written', 1, count($rows));
  check('action_type = quote_created', 'quote_created', $rows[0]['action_type']);
  check('username from session', 'RSantos', $rows[0]['username']);
  check('id_user from session', '7', (string)$rows[0]['id_user']);
  check('message mentions Created', true, strpos($rows[0]['audit_trail'], 'Created') !== false);

  echo "[sync_to_sheet]\n";
  AuditTrailRepository::sync_to_sheet_audit_trail($conexion, $id_rfq);
  $rows = readAudit($conexion, $id_rfq);
  check('two rows total', 2, count($rows));
  check('newest action_type = sync_to_sheet', 'sync_to_sheet', $rows[0]['action_type']);
  check('synced message mentions synced', true, stripos($rows[0]['audit_trail'], 'synced') !== false);

  echo "[break_sync]\n";
  AuditTrailRepository::break_sync_audit_trail($conexion, $id_rfq);
  $rows = readAudit($conexion, $id_rfq);
  check('three rows total', 3, count($rows));
  check('newest action_type = break_sync', 'break_sync', $rows[0]['action_type']);
  check('unsync message mentions unsynced', true, stripos($rows[0]['audit_trail'], 'unsynced') !== false);

  echo "[repeated syncs each write a row]\n";
  AuditTrailRepository::sync_to_sheet_audit_trail($conexion, $id_rfq);
  AuditTrailRepository::sync_to_sheet_audit_trail($conexion, $id_rfq);
  $rows = readAudit($conexion, $id_rfq);
  $syncCount = count(array_filter($rows, fn($r) => $r['action_type'] === 'sync_to_sheet'));
  check('three sync_to_sheet rows after 3 syncs', 3, $syncCount);

} finally {
  $conexion->rollBack();
}

echo "\n==== $pass passed, $fail failed ====\n";
exit($fail === 0 ? 0 : 1);
