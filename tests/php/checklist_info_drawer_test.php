<?php
/**
 * Integration test for the Quote Checklist & Info Drawer feature.
 *
 * Covers:
 *   - Rfq::getChecklistCompletionCount() over the 10-field list at 0/10, a partial
 *     count, and 10/10 — including the Set Aside / GSA "non-default value" rule
 *     (SET_SIDE[0] 'Full & Open' / GSA['na'] 'N/A' must NOT count as complete).
 *   - RepositorioRfq::save_checklist()/save_information() + the matching
 *     AuditTrailRepository::checklist_events()/information_events() still write a
 *     'field_modified' audit_trails row exactly as before — the drawer only changed
 *     how the endpoint responds (JSON vs. redirect), not this underlying logic.
 *
 * Transaction-isolated (ROLLBACK), same pattern as pipeline_table_test.php.
 * Run:  docker exec lamp-php84 php /var/www/html/rfq/tests/php/checklist_info_drawer_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Quote/Rfq.inc.php';
require_once $root . 'app/Quote/RepositorioRfq.inc.php';
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

/** Minimal stand-in for the logged-in user create_audit_trail_modified() reads from the session. */
class FakeSessionUser {
  private $id; private $name;
  public function __construct($id, $name) { $this->id = $id; $this->name = $name; }
  public function obtener_id() { return $this->id; }
  public function obtener_nombre_usuario() { return $this->name; }
}
$_SESSION['user'] = new FakeSessionUser(7, 'RSantos');

function insertQuote($conexion, array $o = []) {
  $f = array_merge([
    'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'CIDTEST',
    'email_code' => 'CID-' . uniqid(), 'type_of_bid' => 'IT',
    'issue_date' => '01/01/1999', 'end_date' => '01/01/1999', 'status' => 0, 'completado' => 0,
    'comments' => 'No comments', 'award' => 0, 'payment_terms' => 'Net 30',
    'address' => '', 'ship_to' => '', 'ship_via' => '', 'taxes' => 0, 'profit' => 0,
    'additional' => '', 'shipping_cost' => 0, 'shipping' => '', 'fullfillment' => 0,
    'contract_number' => '', 'total_price' => 0,
    'invoice' => 0, 'submitted_invoice' => 0, 'deleted' => 0, 'name' => 'Checklist Drawer Test',
  ], $o);
  $cols = array_keys($f);
  $ph = array_map(fn($c) => ':' . $c, $cols);
  $sql = 'INSERT INTO rfq (' . implode(',', $cols) . ') VALUES (' . implode(',', $ph) . ')';
  $stmt = $conexion->prepare($sql);
  foreach ($f as $c => $v) { $stmt->bindValue(':' . $c, $v); }
  $stmt->execute();
  return (int)$conexion->lastInsertId();
}

function readAudit($conexion, $id_rfq) {
  $stmt = $conexion->prepare("SELECT action_type, audit_trail FROM audit_trails WHERE id_rfq = :id ORDER BY id DESC");
  $stmt->bindValue(':id', $id_rfq, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$conexion->beginTransaction();

try {
  echo "[checklist completeness: 0 of 10]\n";
  $idEmpty = insertQuote($conexion, [
    'contract_number' => '', 'client' => null, 'poc' => null, 'co' => null, 'ship_to' => '',
    'set_side' => null, 'gsa' => null, 'estimated_delivery_date' => null,
    'file_document' => null, 'accounting' => null,
  ]);
  $qEmpty = RepositorioRfq::obtener_cotizacion_por_id($conexion, $idEmpty);
  check('0 of 10 when nothing set', 0, $qEmpty->getChecklistCompletionCount());

  echo "[checklist completeness: default Set Aside / GSA don't count]\n";
  $idPartial = insertQuote($conexion, [
    'contract_number' => 'C-100', 'client' => 'Client X', 'poc' => null, 'co' => null, 'ship_to' => '',
    'set_side' => 'Full & Open', 'gsa' => 'na', 'estimated_delivery_date' => null,
    'file_document' => null, 'accounting' => 'dos_payment',
  ]);
  $qPartial = RepositorioRfq::obtener_cotizacion_por_id($conexion, $idPartial);
  check('3 of 10 — contract number, client, accounting only', 3, $qPartial->getChecklistCompletionCount());

  echo "[checklist completeness: 10 of 10]\n";
  $idFull = insertQuote($conexion, [
    'contract_number' => 'C-200', 'client' => 'Client Y', 'poc' => 'POC Name', 'co' => 'CO Name',
    'ship_to' => '123 Main St', 'set_side' => 'SB', 'gsa' => 'open_market',
    'estimated_delivery_date' => '2026-08-01',
    'file_document' => 'proposal|contract_confirmation', 'accounting' => 'dos_payment|wawf',
  ]);
  $qFull = RepositorioRfq::obtener_cotizacion_por_id($conexion, $idFull);
  check('10 of 10 when every field set', 10, $qFull->getChecklistCompletionCount());

  echo "[save_checklist + checklist_events audit trail]\n";
  $idSave = insertQuote($conexion, ['contract_number' => 'ORIG-1', 'ship_to' => 'Old address']);
  $ok = RepositorioRfq::save_checklist(
    $conexion, 'Old address', 1, 'CID-TEST', 'CIDTEST', 'NEW-1', '', '', '', 'Client Z',
    'Full & Open', '', '', null, '', '', '', '', 'na', '', null, $idSave
  );
  check('save_checklist reports success', true, $ok);
  AuditTrailRepository::checklist_events($conexion, 'NEW-1', 'ORIG-1', 'CID-TEST', 'CID-TEST', 'CIDTEST', 'CIDTEST', '1', '1', 'Old address', 'Old address', $idSave);
  $rows = readAudit($conexion, $idSave);
  check('exactly one field_modified row (only contract number changed)', 1, count($rows));
  check('action_type = field_modified', 'field_modified', $rows[0]['action_type']);
  check('message mentions Contract Number', true, strpos($rows[0]['audit_trail'], 'Contract Number') !== false);
  check('message shows old > new', true, strpos($rows[0]['audit_trail'], 'ORIG-1 > NEW-1') !== false);
  $savedChecklist = RepositorioRfq::obtener_cotizacion_por_id($conexion, $idSave);
  check('persisted contract_number', 'NEW-1', $savedChecklist->obtener_contract_number());

  echo "[save_checklist: no-op save writes no audit row]\n";
  AuditTrailRepository::checklist_events($conexion, 'NEW-1', 'NEW-1', 'CID-TEST', 'CID-TEST', 'CIDTEST', 'CIDTEST', '1', '1', 'Old address', 'Old address', $idSave);
  $rowsAfterNoop = readAudit($conexion, $idSave);
  check('still exactly one row after an unchanged re-save', 1, count($rowsAfterNoop));

  echo "[save_information + information_events audit trail]\n";
  $idInfo = insertQuote($conexion, ['ship_via' => 'GROUND']);
  $ok2 = RepositorioRfq::save_information(
    $conexion, null, null, 'Bill to address', 'BEST WAY', 'IT', '01/01/1999', '01/01/1999',
    1, 'CID-TEST', 'CIDTEST', '', 'No comments', '', 4, $idInfo
  );
  check('save_information reports success', true, $ok2);
  AuditTrailRepository::information_events(
    $conexion, 'IT', 'IT', '01/01/1999', '01/01/1999', '01/01/1999', '01/01/1999', '', '', '', '',
    'BEST WAY', 'GROUND', 'CID-TEST', 'CID-TEST', '1', '1', 'CIDTEST', 'CIDTEST', '', '', 'No comments', 'No comments',
    $idInfo
  );
  $rowsInfo = readAudit($conexion, $idInfo);
  check('exactly one field_modified row (only ship via changed)', 1, count($rowsInfo));
  check('message mentions Ship Via', true, strpos($rowsInfo[0]['audit_trail'], 'Ship Via') !== false);
  $savedInfo = RepositorioRfq::obtener_cotizacion_por_id($conexion, $idInfo);
  check('persisted ship_via', 'BEST WAY', $savedInfo->obtener_ship_via());
} finally {
  $conexion->rollBack();
  Conexion::cerrar_conexion();
}

echo "\n==== $pass passed, $fail failed ====\n";
exit($fail === 0 ? 0 : 1);
