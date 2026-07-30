<?php
/**
 * Integration test for the Documents Drawer Tab feature.
 *
 * No schema/backend changes were made for this feature — files stay filesystem-only
 * under documentos/{id_rfq}/ (per features/documents-drawer-tab.md's "Data Model
 * Changes: None"). This test exercises the still-unchanged backend the new Documents
 * tab widget talks to:
 *   - Input::save_files() writes the uploaded file and a document_updated/uploaded
 *     audit_trails row (same call load_img.php makes).
 *   - The directory-scan file count (same expression edicion_cotizacion_recuperada.inc.php
 *     uses for the status-card count, and get_quote_files.php uses for the drawer list)
 *     reflects added/removed files.
 *   - Deleting a file (same unlink + document_updated/deleted audit call delete_document.php
 *     makes) removes it from disk and from that scan, and logs the audit row.
 *
 * DB rows are transaction-isolated (ROLLBACK); the temp documentos/ directory this test
 * creates is removed in a finally block since filesystem writes aren't part of the transaction.
 * Run:  docker exec lamp-php84 php /var/www/html/rfq/tests/php/documents_drawer_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Quote/Rfq.inc.php';
require_once $root . 'app/Quote/RepositorioRfq.inc.php';
require_once $root . 'app/Quote/AuditTrail.inc.php';
require_once $root . 'app/Quote/AuditTrailRepository.inc.php';
require_once $root . 'app/Utilities/Input.inc.php';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = $expected === $actual;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

/** Minimal stand-in for the logged-in user AuditTrailRepository reads from the session. */
class FakeSessionUser {
  private $id; private $name;
  public function __construct($id, $name) { $this->id = $id; $this->name = $name; }
  public function obtener_id() { return $this->id; }
  public function obtener_nombre_usuario() { return $this->name; }
}
$_SESSION['user'] = new FakeSessionUser(7, 'RSantos');

function insertQuote($conexion, array $o = []) {
  $f = array_merge([
    'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'DOCTEST',
    'email_code' => 'DOC-' . uniqid(), 'type_of_bid' => 'IT',
    'issue_date' => '01/01/1999', 'end_date' => '01/01/1999', 'status' => 0, 'completado' => 0,
    'comments' => 'No comments', 'award' => 0, 'payment_terms' => 'Net 30',
    'address' => '', 'ship_to' => '', 'ship_via' => '', 'taxes' => 0, 'profit' => 0,
    'additional' => '', 'shipping_cost' => 0, 'shipping' => '', 'fullfillment' => 0,
    'contract_number' => '', 'total_price' => 0,
    'invoice' => 0, 'submitted_invoice' => 0, 'deleted' => 0, 'name' => 'Documents Drawer Test',
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
  $stmt = $conexion->prepare("SELECT action_type, audit_trail FROM audit_trails WHERE id_rfq = :id ORDER BY id ASC");
  $stmt->bindValue(':id', $id_rfq, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/** Same expression edicion_cotizacion_recuperada.inc.php uses for the live status-card count. */
function scanDocCount($path) {
  return is_dir($path) ? count(array_diff(scandir($path), ['.', '..'])) : 0;
}

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$conexion->beginTransaction();

$tmpDir = sys_get_temp_dir() . '/doc_widget_test_' . uniqid();

try {
  $idQuote = insertQuote($conexion);

  echo "[directory scan count: missing directory]\n";
  check('0 when the documentos/{id} directory does not exist yet', 0, scanDocCount($tmpDir));

  echo "[Input::save_files: writes a document_updated/uploaded audit row]\n";
  // move_uploaded_file() requires a genuine HTTP-uploaded file (it checks is_uploaded_file()
  // internally) and always no-ops under a plain CLI run — so this only exercises the
  // audit-trail half of save_files(), which is the half load_img.php relies on to feed the
  // Audit Trail modal's "document uploaded" entries.
  $tmpUpload = tempnam(sys_get_temp_dir(), 'doc');
  file_put_contents($tmpUpload, 'test file contents');
  mkdir($tmpDir, 0777, true);
  Input::save_files($tmpDir, [
    'name'     => ['Specifications.pdf'],
    'tmp_name' => [$tmpUpload],
    'error'    => [0],
    'size'     => [filesize($tmpUpload)],
  ], $idQuote);
  $rowsAfterUpload = readAudit($conexion, $idQuote);
  check('exactly one audit row after upload', 1, count($rowsAfterUpload));
  check('action_type is document_updated', 'document_updated', $rowsAfterUpload[0]['action_type']);
  check('message says uploaded', true, strpos($rowsAfterUpload[0]['audit_trail'], 'uploaded') !== false);
  check('message names the file', true, strpos($rowsAfterUpload[0]['audit_trail'], 'Specifications.pdf') !== false);

  echo "[directory scan count: same expression the status card and get_quote_files.php use]\n";
  copy($tmpUpload, $tmpDir . '/Specifications.pdf');
  check('scan count is 1 with one file on disk', 1, scanDocCount($tmpDir));
  copy($tmpUpload, $tmpDir . '/Bid_Bond.docx');
  check('scan count is 2 with two files on disk', 2, scanDocCount($tmpDir));

  echo "[delete_document.php's logic: unlink + document_updated/deleted audit row]\n";
  $target = $tmpDir . '/Specifications.pdf';
  $deleted = unlink($target);
  AuditTrailRepository::document_updated($conexion, 'deleted', 'Specifications.pdf', $idQuote);
  check('unlink reports success', true, $deleted);
  check('file removed from disk', false, file_exists($target));
  check('scan count drops back to 1 after delete', 1, scanDocCount($tmpDir));
  $rowsAfterDelete = readAudit($conexion, $idQuote);
  check('two audit rows total (1 uploaded + 1 deleted)', 2, count($rowsAfterDelete));
  check('most recent action_type is document_updated', 'document_updated', $rowsAfterDelete[1]['action_type']);
  check('most recent message says deleted', true, strpos($rowsAfterDelete[1]['audit_trail'], 'deleted') !== false);
} finally {
  $conexion->rollBack();
  Conexion::cerrar_conexion();
  if (is_dir($tmpDir)) {
    foreach (glob($tmpDir . '/*') as $f) { unlink($f); }
    rmdir($tmpDir);
  }
}

echo "\n==== $pass passed, $fail failed ====\n";
exit($fail === 0 ? 0 : 1);
