<?php
/**
 * Integration test for: quote Name synced to the SharePoint sheet as an HTML entity
 * ("Acme & Sons" -> "Acme &amp; Sons") — bugs/sheet-sync-html-escaped-name.md.
 *
 * Root cause: createAndInsertQuote() (plantillas/quote/validacion_registro_cotizacion.inc.php)
 * ran the New Quote `name` through htmlspecialchars() BEFORE storage, so rfq.name held the
 * already-escaped string. Every consumer of Rfq::getName() inherited it — notably
 * SheetSyncService::buildRowValues() column D, a plain-text Graph context that never decodes
 * the entity back. The rest of the app (and the later edit path, save_information.php)
 * stores raw text and escapes once at render.
 *
 * This test drives the REAL New Quote write path (createAndInsertQuote) with a faked
 * $_POST/$_SESSION, then reads back what was stored and what the sheet row would carry.
 * Transaction-isolated (ROLLBACK); the redirect script createAndInsertQuote echoes is
 * swallowed with output buffering and the documents dir it makes is pointed at a temp dir.
 *
 * Run:  docker exec lamp-php84 php /var/www/html/rfq/tests/php/quote_name_escaping_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/routes.inc.php';
spl_autoload_register(function ($class) use ($root) {
  foreach (glob($root . "app/*/$class.inc.php") as $file) { require_once $file; }
});

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = $expected === $actual;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

/** Minimal stand-in for the logged-in user the audit helpers read from the session. */
class FakeSessionUser {
  private $id; private $name;
  public function __construct($id, $name) { $this->id = $id; $this->name = $name; }
  public function obtener_id() { return $this->id; }
  public function obtener_nombre_usuario() { return $this->name; }
}

// Defines createAndInsertQuote(); $_POST is empty so the top-level submit block is skipped.
include_once $root . 'plantillas/quote/validacion_registro_cotizacion.inc.php';

$docRoot = sys_get_temp_dir() . '/rfq_name_test_' . uniqid();
$_SERVER['DOCUMENT_ROOT'] = $docRoot;
$_FILES['documentos'] = ['name' => [], 'tmp_name' => []];

$c = Conexion::obtener_conexion();
$c->beginTransaction();

try {
  $uname = 'qn_test_' . uniqid();
  $stmt = $c->prepare("INSERT INTO usuarios (nombre_usuario, password, nombres, apellidos, cargo, email, status)
                       VALUES (:u, 'x', 'Name', 'Tester', '3', :e, 1)");
  $stmt->execute([':u' => $uname, ':e' => $uname . '@test.local']);
  $userId = (int)$c->lastInsertId();
  $_SESSION['user'] = new FakeSessionUser($userId, $uname);

  /** Submit a New Quote through the real write path; return [stored rfq.name, Rfq, sheet column D]. */
  $createQuote = function ($rawName) use ($c, $userId, $uname) {
    $code = 'QN-' . uniqid();
    $_POST = [
      'canal' => 'AUDITTEST', 'email_code' => $code, 'type_of_bid' => 'IT',
      'issue_date' => '01/01/2030', 'end_date' => '01/02/2030 10:00', 'internal_due_date' => '12/31/2029',
      'usuario_designado' => $uname, 'reference_url' => '', 'name' => $rawName,
    ];
    $validador = new ValidadorCotizacionRegistro(
      $c, $code, $_POST['issue_date'], $_POST['end_date'], $_POST['internal_due_date'],
      $_POST['type_of_bid'], $uname, $_POST['canal']
    );
    ob_start();
    createAndInsertQuote($validador, $userId);
    ob_end_clean();

    $stmt = $c->prepare('SELECT id, name FROM rfq WHERE email_code = :code');
    $stmt->execute([':code' => $code]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $quote = RepositorioRfq::obtener_cotizacion_por_id($c, $row['id']);
    $sheetRow = (new ReflectionMethod('SheetSyncService', 'buildRowValues'))->invoke(null, $quote, $uname);
    return [$row['name'], $quote, $sheetRow[3]];
  };

  echo "[ampersand — the reported case]\n";
  [$stored, $quote, $colD] = $createQuote('Acme & Sons Contract');
  check('rfq.name stored raw', 'Acme & Sons Contract', $stored);
  check('Rfq::getName() returns raw', 'Acme & Sons Contract', $quote->getName());
  check('sheet column D carries the literal "&", not "&amp;"', 'Acme & Sons Contract', $colD);

  echo "\n[other HTML-special characters]\n";
  $special = 'O\'Reilly <IT> "Support" & More';
  [$stored, , $colD] = $createQuote($special);
  check('rfq.name stored raw (quotes, angle brackets, &)', $special, $stored);
  check('sheet column D carries them literally', $special, $colD);

  echo "\n[trim + blank handling unchanged]\n";
  [$stored] = $createQuote("  Padded Name  ");
  check('surrounding whitespace trimmed', 'Padded Name', $stored);
  [$stored] = $createQuote('');
  check('blank name stores NULL', null, $stored);

} finally {
  $c->rollBack();
  if (is_dir($docRoot)) {
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($docRoot, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $f) {
      $f->isDir() ? rmdir($f->getPathname()) : unlink($f->getPathname());
    }
    rmdir($docRoot);
  }
}

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
