<?php
/**
 * Integration test for Pipeline Table View + Quote Watchers.
 *
 * Covers:
 *   • QuoteWatcherRepository watch/unwatch/isWatching/getWatcherIds (idempotent + unique)
 *   • WatcherNotificationService::autoSubscribeDesignated adds the designated user
 *   • WatcherNotificationService::notify inserts one in-app notification per watcher,
 *     never for the acting user
 *   • PipelineTableRepository::getPage — period scoping, status filter, designated-user
 *     filter, the per-user watched flag, and custom date range (incl. inverted = empty)
 *
 * Transaction-isolated (ROLLBACK). Rows live in year 2099 so the period filter matches
 * only what this test inserts (real rows carry a different/NULL created_at).
 * Run:  docker exec lamp-php84 php /var/www/html/rfq/tests/php/quote_watchers_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/routes.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/User/Usuario.inc.php';
require_once $root . 'app/User/RepositorioUsuario.inc.php';
require_once $root . 'app/Quote/NotificationRepository.inc.php';
require_once $root . 'app/Quote/QuoteWatcherRepository.inc.php';
require_once $root . 'app/Setting/NotificationMailboxRepository.inc.php';
require_once $root . 'app/Utilities/NotificationEmail.inc.php';
require_once $root . 'app/Quote/WatcherNotificationService.inc.php';
require_once $root . 'app/Report/PipelineMetricsRepository.inc.php';
require_once $root . 'app/Report/PipelineTableRepository.inc.php';

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  $ok = $expected === $actual;
  if ($ok) { $pass++; echo "  PASS  $label\n"; }
  else     { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

Conexion::abrir_conexion();
$c = Conexion::obtener_conexion();
$c->beginTransaction();

try {
  // Ensure the shared mailbox is disconnected so the email leg is a safe no-op (no network).
  NotificationMailboxRepository::clear($c);

  // --- two test users (watchers), in-app only ---
  $mkUser = function ($name) use ($c) {
    $stmt = $c->prepare("INSERT INTO usuarios (nombre_usuario, password, nombres, apellidos, cargo, email, status, notif_inapp, notif_email)
                         VALUES (:u, 'x', :n, 'Test', '3', :e, 1, 1, 0)");
    $stmt->execute([':u' => $name, ':n' => $name, ':e' => $name . '@test.local']);
    return (int)$c->lastInsertId();
  };
  $userA = $mkUser('watch_a_' . uniqid());
  $userB = $mkUser('watch_b_' . uniqid());

  // --- three quotes in 2099 with distinct derived statuses ---
  $mkRfq = function ($fields) use ($c) {
    $cols = array_merge([
      'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'FedBid', 'email_code' => 'WT-TEST',
      'type_of_bid' => 'IT', 'status' => 0, 'completado' => 0, 'comments' => '', 'award' => 0,
      'total_price' => 1000, 'deleted' => 0, 'name' => 'Watcher test quote', 'file_document' => 'a.pdf|b.xlsx',
      'created_at' => '2099-06-15 10:00:00',
    ], $fields);
    $keys = array_keys($cols);
    $sql = 'INSERT INTO rfq (' . implode(',', $keys) . ') VALUES (:' . implode(',:', $keys) . ')';
    $stmt = $c->prepare($sql);
    foreach ($cols as $k => $v) $stmt->bindValue(':' . $k, $v);
    $stmt->execute();
    return (int)$c->lastInsertId();
  };
  $qBid       = $mkRfq(['completado' => 1]);                       // 'bid'
  $qSubmitted = $mkRfq(['completado' => 1, 'status' => 1]);        // 'submitted'
  $qAward     = $mkRfq(['completado' => 1, 'award' => 1, 'usuario_designado' => $userB]); // 'award'

  /* ---------- QuoteWatcherRepository ---------- */
  check('not watching initially', false, QuoteWatcherRepository::isWatching($c, $qBid, $userA));
  QuoteWatcherRepository::watch($c, $qBid, $userA);
  QuoteWatcherRepository::watch($c, $qBid, $userA); // idempotent
  check('watching after watch', true, QuoteWatcherRepository::isWatching($c, $qBid, $userA));
  check('unique — one watcher row', [$userA], QuoteWatcherRepository::getWatcherIds($c, $qBid));
  QuoteWatcherRepository::unwatch($c, $qBid, $userA);
  check('not watching after unwatch', false, QuoteWatcherRepository::isWatching($c, $qBid, $userA));

  /* ---------- autoSubscribeDesignated ---------- */
  WatcherNotificationService::autoSubscribeDesignated($c, $qAward, $userB);
  check('designated auto-subscribed', true, QuoteWatcherRepository::isWatching($c, $qAward, $userB));
  WatcherNotificationService::autoSubscribeDesignated($c, $qAward, 0); // ignore empty
  check('empty designated ignored', [$userB], QuoteWatcherRepository::getWatcherIds($c, $qAward));

  /* ---------- notify: one in-app per watcher, never the actor ---------- */
  QuoteWatcherRepository::watch($c, $qAward, $userA);
  $before = (int)$c->query("SELECT COUNT(*) FROM notifications WHERE id_rfq = $qAward")->fetchColumn();
  WatcherNotificationService::notify($c, $qAward, $userB, 'Quote #' . $qAward . ' status changed to Awarded');
  $after = (int)$c->query("SELECT COUNT(*) FROM notifications WHERE id_rfq = $qAward")->fetchColumn();
  check('one notification inserted (userA only, not actor userB)', 1, $after - $before);
  $recipient = (int)$c->query("SELECT id_user FROM notifications WHERE id_rfq = $qAward ORDER BY id DESC LIMIT 1")->fetchColumn();
  check('notification went to the non-actor watcher', $userA, $recipient);

  /* ---------- PipelineTableRepository ---------- */
  $year = ['mode' => 'year', 'year' => 2099];
  $noF  = ['quoteId' => '', 'channel' => '', 'emailCode' => '', 'statuses' => [], 'bidType' => '', 'user' => ''];

  $all = PipelineTableRepository::getPage($c, $year, $noF, $userA, 0);
  check('table: 3 quotes in 2099', 3, $all['total']);

  // watched flag reflects the current user (userA watches qAward only)
  $byId = [];
  foreach ($all['rows'] as $r) $byId[$r['id']] = $r;
  check('qAward watched by userA', true, $byId[$qAward]['watched']);
  check('qBid not watched by userA', false, $byId[$qBid]['watched']);
  check('row carries docs list', 2, count($byId[$qBid]['docs']));
  check('row status label present', 'Award', $byId[$qAward]['statusLabel']);

  // status filter
  $awardOnly = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['statuses' => ['award']]), $userA, 0);
  check('status filter award -> 1', 1, $awardOnly['total']);

  // designated-user filter (qAward is assigned to userB)
  $userFilter = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['user' => $userB]), $userA, 0);
  check('designated-user filter -> 1', 1, $userFilter['total']);

  // custom range covering June 2099
  $custom = PipelineTableRepository::getPage($c, ['mode' => 'custom', 'from' => '2099-01-01', 'to' => '2099-12-31'], $noF, $userA, 0);
  check('custom range covering the rows -> 3', 3, $custom['total']);
  // inverted range -> empty, no error
  $inverted = PipelineTableRepository::getPage($c, ['mode' => 'custom', 'from' => '2099-12-31', 'to' => '2099-01-01'], $noF, $userA, 0);
  check('inverted custom range -> 0', 0, $inverted['total']);

} finally {
  $c->rollBack();
  Conexion::cerrar_conexion();
}

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
