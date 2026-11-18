<?php
/**
 * Integration test for features/retire-tracking-sheet.md:
 *   - Letter of Intent / Submission Method: insert + update round-trip, whitelist (invalid -> NULL,
 *     blank -> NULL), options live in Rfq constants.
 *   - rfq.name is stored raw on create and on edit (moved here from the deleted
 *     quote_name_escaping_test.php, whose subject was the sheet's column D).
 *   - AuditTrailRepository::information_events writes one row per changed bid field, none if unchanged.
 *   - PipelineTableRepository returns the tracking columns, and the three new filters narrow results.
 *   - PartnerSyncService::isUntouched() no longer looks at sheet_row.
 *
 * Needs sql/retire_tracking_sheet_migration.sql applied (tests never run DDL).
 * Transaction-isolated (ROLLBACK).
 *
 * Run:  docker exec lamp-php84 php /var/www/html/rfq/tests/php/retire_tracking_sheet_test.php
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

/** $a[$k], or 'missing' when the key is absent (?? can't tell NULL from absent). */
function col(array $a, $k) { return array_key_exists($k, $a) ? $a[$k] : 'missing'; }

/** Minimal stand-in for the logged-in user the audit helpers read from the session. */
class FakeSessionUser {
  private $id; private $name;
  public function __construct($id, $name) { $this->id = $id; $this->name = $name; }
  public function obtener_id() { return $this->id; }
  public function obtener_nombre_usuario() { return $this->name; }
}

// Defines createAndInsertQuote(); $_POST is empty so the top-level submit block is skipped.
include_once $root . 'plantillas/quote/validacion_registro_cotizacion.inc.php';

$docRoot = sys_get_temp_dir() . '/rfq_rts_test_' . uniqid();
$_SERVER['DOCUMENT_ROOT'] = $docRoot;
$_FILES['documentos'] = ['name' => [], 'tmp_name' => []];

$c = Conexion::obtener_conexion();
$c->beginTransaction();

try {
  $uname = 'rts_test_' . uniqid();
  $stmt = $c->prepare("INSERT INTO usuarios (nombre_usuario, password, nombres, apellidos, cargo, email, status)
                       VALUES (:u, 'x', 'Sheet', 'Tester', '3', :e, 1)");
  $stmt->execute([':u' => $uname, ':e' => $uname . '@test.local']);
  $userId = (int)$c->lastInsertId();
  $_SESSION['user'] = new FakeSessionUser($userId, $uname);

  /** Submit a New Quote through the real write path; return the stored rfq row. */
  $createQuote = function (array $extra) use ($c, $userId, $uname) {
    $code = 'RTS-' . uniqid();
    $_POST = array_merge([
      'canal' => 'AUDITTEST', 'email_code' => $code, 'type_of_bid' => 'IT',
      'issue_date' => '01/01/2030', 'end_date' => '01/02/2030 10:00', 'internal_due_date' => '12/31/2029',
      'usuario_designado' => $uname, 'reference_url' => '', 'name' => 'Retire sheet test',
    ], $extra);
    $validador = new ValidadorCotizacionRegistro(
      $c, $code, $_POST['issue_date'], $_POST['end_date'], $_POST['internal_due_date'],
      $_POST['type_of_bid'], $uname, $_POST['canal'], $_POST['name']
    );
    ob_start();
    createAndInsertQuote($validador, $userId);
    ob_end_clean();
    $stmt = $c->prepare('SELECT * FROM rfq WHERE email_code = :code');
    $stmt->execute([':code' => $code]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  };

  /** Read the audit rows for a quote, newest first. */
  $audit = function ($id) use ($c) {
    $stmt = $c->prepare("SELECT action_type, audit_trail FROM audit_trails WHERE id_rfq = :id AND action_type = 'field_modified' ORDER BY id DESC");
    $stmt->execute([':id' => $id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  };

  echo "[options live in one place]\n";
  check('Rfq::LETTER_OF_INTENT_OPTIONS', ['YES', 'NO', 'TBD'], defined('Rfq::LETTER_OF_INTENT_OPTIONS') ? Rfq::LETTER_OF_INTENT_OPTIONS : null);
  check('Rfq::SUBMISSION_METHOD_OPTIONS', ['PLATFORM', 'EMAIL', 'MAIL'], defined('Rfq::SUBMISSION_METHOD_OPTIONS') ? Rfq::SUBMISSION_METHOD_OPTIONS : null);

  echo "\n[insert round-trip — New Quote]\n";
  $row = $createQuote(['letter_of_intent' => 'TBD', 'submission_method' => 'EMAIL']);
  check('letter_of_intent stored', 'TBD', col($row, 'letter_of_intent'));
  check('submission_method stored', 'EMAIL', col($row, 'submission_method'));
  $quote = RepositorioRfq::obtener_cotizacion_por_id($c, $row['id']);
  check('Rfq::getLetterOfIntent()', 'TBD', method_exists($quote, 'getLetterOfIntent') ? $quote->getLetterOfIntent() : 'no getter');
  check('Rfq::getSubmissionMethod()', 'EMAIL', method_exists($quote, 'getSubmissionMethod') ? $quote->getSubmissionMethod() : 'no getter');
  check('no sync_to_sheet column left', false, array_key_exists('sync_to_sheet', $row));
  check('no sheet_row column left', false, array_key_exists('sheet_row', $row));

  $row = $createQuote(['letter_of_intent' => 'MAYBE', 'submission_method' => 'FAX']);
  check('invalid letter_of_intent stored as NULL', null, col($row, 'letter_of_intent'));
  check('invalid submission_method stored as NULL', null, col($row, 'submission_method'));

  $row = $createQuote(['letter_of_intent' => '', 'submission_method' => '']);
  check('blank letter_of_intent stored as NULL', null, col($row, 'letter_of_intent'));
  check('blank submission_method stored as NULL', null, col($row, 'submission_method'));

  $row = $createQuote([]);
  check('omitted fields stored as NULL', [null, null], [col($row, 'letter_of_intent'), col($row, 'submission_method')]);

  echo "\n[rfq.name stored raw on create]\n";
  $row = $createQuote(['name' => 'Acme & Sons Contract']);
  check('ampersand stored raw', 'Acme & Sons Contract', $row['name']);
  $special = 'O\'Reilly <IT> "Support" & More';
  $row = $createQuote(['name' => $special]);
  check('quotes / angle brackets stored raw', $special, $row['name']);
  $row = $createQuote(['name' => '  Padded Name  ']);
  check('surrounding whitespace trimmed', 'Padded Name', $row['name']);

  echo "\n[update round-trip — Information drawer]\n";
  $id = (int)$createQuote([])['id'];
  $save = function ($loi, $method) use ($c, $id, $userId) {
    return RepositorioRfq::save_information(
      $c, null, null, '', '', 'IT', '01/01/2030', '01/02/2030 10:00', $userId, 'RTS-UPD', 'AUDITTEST', '', 'No comments', '', '', $id,
      null, null, null, '12/31/2029', null, $loi, $method
    );
  };
  $read = function () use ($c, $id) {
    $stmt = $c->prepare('SELECT letter_of_intent, submission_method FROM rfq WHERE id = :id');
    $stmt->execute([':id' => $id]);
    return array_values($stmt->fetch(PDO::FETCH_ASSOC) ?: []);
  };
  $save('YES', 'PLATFORM');
  check('valid values saved', ['YES', 'PLATFORM'], $read());
  $save('NO', 'MAIL');
  check('changed values saved', ['NO', 'MAIL'], $read());
  $save('yes please', 'PIGEON');
  check('invalid values saved as NULL', [null, null], $read());
  $save('TBD', 'EMAIL');
  $save('', '');
  check('blank values saved as NULL', [null, null], $read());

  check('RepositorioRfq::update_name exists', true, method_exists('RepositorioRfq', 'update_name'));
  if (method_exists('RepositorioRfq', 'update_name')) {
    RepositorioRfq::update_name($c, $id, 'Edited & <Renamed>');
    $stmt = $c->prepare('SELECT name FROM rfq WHERE id = :id');
    $stmt->execute([':id' => $id]);
    check('edited name stored raw', 'Edited & <Renamed>', $stmt->fetchColumn());
  }

  echo "\n[audit: bid_field_events on an Information save]\n";
  // save_information.php compares the rfq row read before the save with the quote re-read after
  // it, so both sides are DB-shaped: ints for yes/no, 'Y-m-d H:i:s' for the deadline.
  $events = function (array $before, array $after) use ($c, $id) {
    AuditTrailRepository::bid_field_events($c, new Rfq($before), new Rfq($after), $id);
  };
  $blank  = ['letter_of_intent' => null, 'submission_method' => null, 'qa' => null, 'qa_deadline' => null, 'resumes' => null, 'site_visit' => null];
  $stored = ['letter_of_intent' => 'YES', 'submission_method' => 'EMAIL', 'qa' => 1, 'qa_deadline' => '2030-12-01 10:00:00', 'resumes' => 0, 'site_visit' => 1];

  $before = count($audit($id));
  $events($blank, $stored);
  $rows = $audit($id);
  check('six bid-field changes -> six audit rows', 6, count($rows) - $before);
  $text = implode("\n", array_column(array_slice($rows, 0, 6), 'audit_trail'));
  foreach (['Letter of Intent', 'Submission Method', 'Q&amp;A', 'Q&amp;A Deadline', 'Resumes', 'Site Visit'] as $label) {
    check("row for $label", true, strpos($text, "<b>$label</b>") !== false);
  }
  check('LOI shows — > YES', true, strpos($text, '— > YES') !== false);
  check('Submission Method shows — > EMAIL', true, strpos($text, '— > EMAIL') !== false);
  check('Resumes shows — > NO', true, strpos($text, '— > NO') !== false);
  check('Q&A Deadline shows MM/DD/YYYY HH:mm', true, strpos($text, '— > 12/01/2030 10:00') !== false);

  $before = count($audit($id));
  $events($stored, $stored);
  check('no change -> no audit rows', $before, count($audit($id)));

  $events($stored, array_merge($stored, ['submission_method' => null]));
  $rows = $audit($id);
  check('one change -> one audit row', $before + 1, count($rows));
  check('cleared value shows EMAIL > —', true, strpos($rows[0]['audit_trail'], 'EMAIL > —') !== false);

  echo "\n[pipeline table: tracking columns + filters]\n";
  $mkRfq = function ($fields) use ($c) {
    $cols = array_merge([
      'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'FedBid', 'email_code' => 'RTS-PT',
      'type_of_bid' => 'IT', 'status' => 0, 'completado' => 1, 'comments' => '', 'award' => 0,
      'total_price' => 1000, 'deleted' => 0, 'name' => 'Plain quote', 'created_at' => '2098-03-15 10:00:00',
    ], $fields);
    $keys = array_keys($cols);
    $stmt = $c->prepare('INSERT INTO rfq (' . implode(',', $keys) . ') VALUES (:' . implode(',:', $keys) . ')');
    foreach ($cols as $k => $v) $stmt->bindValue(':' . $k, $v);
    $stmt->execute();
    return (int)$c->lastInsertId();
  };
  $qFull = $mkRfq(['name' => 'Janitorial <services> & more', 'qa' => 1, 'qa_deadline' => '2098-04-01 14:30:00', 'resumes' => 0,
                   'site_visit' => 1, 'letter_of_intent' => 'YES', 'submission_method' => 'PLATFORM']);
  $qMail = $mkRfq(['name' => 'Office furniture', 'letter_of_intent' => 'TBD', 'submission_method' => 'MAIL']);
  $qNone = $mkRfq(['name' => 'Janitorial supplies']);

  $period = ['mode' => 'year', 'year' => 2098];
  $page = PipelineTableRepository::getPage($c, $period, [], 0);
  check('3 quotes in 2098', 3, $page['total']);
  $byId = [];
  foreach ($page['rows'] as $r) $byId[$r['id']] = $r;
  $full = $byId[$qFull]; $none = $byId[$qNone];
  check('description is raw name', 'Janitorial <services> & more', col($full, 'description'));
  check('qa -> YES', 'YES', col($full, 'qa'));
  check('qaDeadline -> MM/DD/YYYY HH:mm', '04/01/2098 14:30', col($full, 'qaDeadline'));
  check('resumes -> NO', 'NO', col($full, 'resumes'));
  check('siteVisit -> YES', 'YES', col($full, 'siteVisit'));
  check('letterOfIntent', 'YES', col($full, 'letterOfIntent'));
  check('submissionMethod', 'PLATFORM', col($full, 'submissionMethod'));
  check('unset tracking values are null (render as —)', [null, null, null, null, null, null],
    [col($none, 'qa'), col($none, 'qaDeadline'), col($none, 'resumes'), col($none, 'siteVisit'), col($none, 'letterOfIntent'), col($none, 'submissionMethod')]);

  $ids = function ($filters) use ($c, $period) {
    $ids = array_column(PipelineTableRepository::getPage($c, $period, $filters, 0)['rows'], 'id');
    sort($ids);
    return $ids;
  };
  check('Description filter (LIKE)', [$qFull, $qNone], $ids(['description' => 'janitorial']));
  check('Letter of Intent filter', [$qMail], $ids(['letterOfIntent' => 'TBD']));
  check('Submission Method filter', [$qFull], $ids(['submissionMethod' => 'PLATFORM']));
  check('filters AND-combine', [], $ids(['description' => 'janitorial', 'submissionMethod' => 'MAIL']));
  check('filters AND-combine with existing ones', [$qFull], $ids(['description' => 'janitorial', 'letterOfIntent' => 'YES', 'channel' => 'FedBid']));

  echo "\n[partner: isUntouched ignores sheet state]\n";
  $isUntouched = new ReflectionMethod('PartnerSyncService', 'isUntouched');
  $isUntouched->setAccessible(true);
  $untouchedRow = ['id' => $qNone, 'status' => 0, 'completado' => 0, 'award' => 0, 'fullfillment' => 0, 'invoice' => 0,
                   'total_price' => 0, 'comments' => '', 'sheet_row' => 42];
  check('untouched quote with a former sheet_row is still untouched', true, $isUntouched->invoke(null, $c, $untouchedRow));

} catch (Throwable $e) {
  $fail++;
  echo "  FAIL  uncaught: " . get_class($e) . ': ' . $e->getMessage() . "\n";
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
