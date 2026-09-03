<?php
/**
 * Integration test for the Period of Performance feature.
 *
 * Lives on the Checklist tab/drawer (moved from the Information tab after initial
 * launch — see git history). Covers:
 *   - Rfq::obtener_pop_start_date()/obtener_pop_end_date() read the new columns.
 *   - RepositorioRfq::save_checklist() persists a partial range (either side blank)
 *     and a full range.
 *   - AuditTrailRepository::checklist_events() logs a single 'field_modified' row
 *     for Period of Performance when either date changes, and none when unchanged —
 *     same convention as every other Checklist drawer field.
 *
 * Transaction-isolated (ROLLBACK), same pattern as checklist_info_drawer_test.php.
 * Run:  docker exec lamp-php84 php /var/www/html/rfq/tests/php/period_of_performance_test.php
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
class FakeSessionUserPop {
  private $id; private $name;
  public function __construct($id, $name) { $this->id = $id; $this->name = $name; }
  public function obtener_id() { return $this->id; }
  public function obtener_nombre_usuario() { return $this->name; }
}
$_SESSION['user'] = new FakeSessionUserPop(7, 'RSantos');

function insertQuote($conexion, array $o = []) {
  $f = array_merge([
    'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'POPTEST',
    'email_code' => 'POP-' . uniqid(), 'type_of_bid' => 'IT',
    'issue_date' => '01/01/1999', 'end_date' => '01/01/1999', 'status' => 0, 'completado' => 0,
    'comments' => 'No comments', 'award' => 0, 'payment_terms' => 'Net 30',
    'address' => '', 'ship_to' => '', 'ship_via' => '', 'taxes' => 0, 'profit' => 0,
    'additional' => '', 'shipping_cost' => 0, 'shipping' => '', 'fullfillment' => 0,
    'contract_number' => '', 'total_price' => 0,
    'invoice' => 0, 'submitted_invoice' => 0, 'deleted' => 0, 'name' => 'Period of Performance Test',
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

/** save_checklist() with neutral values for every field but Period of Performance. */
function saveChecklistWithPop($conexion, $id_rfq, $popStart, $popEnd) {
  return RepositorioRfq::save_checklist(
    $conexion, 'Old address', 1, 'CID-TEST', 'CIDTEST', 'NEW-1', '', '', '', 'Client Z',
    'Full & Open', '', '', null, '', '', '', '', 'na', '', null, $id_rfq,
    $popStart, $popEnd
  );
}

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$conexion->beginTransaction();

try {
  echo "[new quote has no Period of Performance set]\n";
  $idBlank = insertQuote($conexion);
  $qBlank = RepositorioRfq::obtener_cotizacion_por_id($conexion, $idBlank);
  check('pop_start_date null by default', null, $qBlank->obtener_pop_start_date());
  check('pop_end_date null by default', null, $qBlank->obtener_pop_end_date());

  echo "[save_checklist persists a full range]\n";
  $idFull = insertQuote($conexion);
  $ok = saveChecklistWithPop($conexion, $idFull, '2027-01-01', '2027-12-31');
  check('save_checklist reports success', true, $ok);
  $savedFull = RepositorioRfq::obtener_cotizacion_por_id($conexion, $idFull);
  check('pop_start_date persisted', '2027-01-01', $savedFull->obtener_pop_start_date());
  check('pop_end_date persisted', '2027-12-31', $savedFull->obtener_pop_end_date());

  echo "[save_checklist persists a partial range (start only)]\n";
  $idPartial = insertQuote($conexion);
  saveChecklistWithPop($conexion, $idPartial, '2027-06-01', null);
  $savedPartial = RepositorioRfq::obtener_cotizacion_por_id($conexion, $idPartial);
  check('pop_start_date persisted (start only)', '2027-06-01', $savedPartial->obtener_pop_start_date());
  check('pop_end_date stays null (start only)', null, $savedPartial->obtener_pop_end_date());

  echo "[checklist_events logs Period of Performance as field_modified when changed]\n";
  AuditTrailRepository::checklist_events(
    $conexion, 'NEW-1', 'NEW-1', 'CID-TEST', 'CID-TEST', 'CIDTEST', 'CIDTEST', '1', '1', 'Old address', 'Old address',
    $idFull, '2027-01-01', '', '2027-12-31', ''
  );
  $rows = readAudit($conexion, $idFull);
  check('exactly one field_modified row (only PoP changed)', 1, count($rows));
  check('action_type = field_modified', 'field_modified', $rows[0]['action_type']);
  check('message mentions Period of Performance', true, strpos($rows[0]['audit_trail'], 'Period of Performance') !== false);

  echo "[checklist_events writes no audit row when Period of Performance is unchanged]\n";
  AuditTrailRepository::checklist_events(
    $conexion, 'NEW-1', 'NEW-1', 'CID-TEST', 'CID-TEST', 'CIDTEST', 'CIDTEST', '1', '1', 'Old address', 'Old address',
    $idFull, '2027-01-01', '2027-01-01', '2027-12-31', '2027-12-31'
  );
  $rowsNoop = readAudit($conexion, $idFull);
  check('still exactly one row after an unchanged re-save', 1, count($rowsNoop));

  echo "[checklist_events logs clearing a previously-set range]\n";
  AuditTrailRepository::checklist_events(
    $conexion, 'NEW-1', 'NEW-1', 'CID-TEST', 'CID-TEST', 'CIDTEST', 'CIDTEST', '1', '1', 'Old address', 'Old address',
    $idFull, '', '2027-01-01', '', '2027-12-31'
  );
  $rowsCleared = readAudit($conexion, $idFull);
  check('clearing produces a new field_modified row', 2, count($rowsCleared));

  echo "[checklist_events without Period of Performance args is a no-op (back-compat)]\n";
  $idNoPop = insertQuote($conexion, ['contract_number' => 'ORIG-2']);
  AuditTrailRepository::checklist_events($conexion, 'ORIG-2', 'ORIG-2', 'CID-TEST', 'CID-TEST', 'CIDTEST', 'CIDTEST', '1', '1', '', '', $idNoPop);
  check('no rows written when PoP args are omitted entirely', 0, count(readAudit($conexion, $idNoPop)));

} finally {
  $conexion->rollBack();
  Conexion::cerrar_conexion();
}

echo "\n==== $pass passed, $fail failed ====\n";
exit($fail === 0 ? 0 : 1);
