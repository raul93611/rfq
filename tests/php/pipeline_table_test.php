<?php
/**
 * Integration test for PipelineTableRepository (Bid Pipeline Metrics "Table" view).
 *
 * Covers period scoping, status filter, designated-user filter, and custom date range
 * (including an inverted range returning empty, no error) over the same rfq.created_at
 * cohort the charts use.
 *
 * Transaction-isolated (ROLLBACK). Rows live in year 2099 so the period filter matches
 * only what this test inserts (real rows carry a different/NULL created_at).
 * Run:  docker exec lamp-php84 php /var/www/html/rfq/tests/php/pipeline_table_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/routes.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Quote/Rfq.inc.php';
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
  // --- test users (designated) ---
  $mkUser = function ($name) use ($c) {
    $stmt = $c->prepare("INSERT INTO usuarios (nombre_usuario, password, nombres, apellidos, cargo, email, status, notif_inapp, notif_email)
                         VALUES (:u, 'x', :n, 'Test', '3', :e, 1, 1, 0)");
    $stmt->execute([':u' => $name, ':n' => $name, ':e' => $name . '@test.local']);
    return (int)$c->lastInsertId();
  };
  $userA = $mkUser('pt_a_' . uniqid());
  $userB = $mkUser('pt_b_' . uniqid());

  // --- three quotes in 2099 with distinct derived statuses ---
  $mkRfq = function ($fields) use ($c) {
    $cols = array_merge([
      'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'FedBid', 'email_code' => 'PT-TEST',
      'type_of_bid' => 'IT', 'status' => 0, 'completado' => 0, 'comments' => '', 'award' => 0,
      'total_price' => 1000, 'deleted' => 0, 'name' => 'Pipeline table test quote', 'file_document' => 'a.pdf|b.xlsx',
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
  $qWoi       = $mkRfq(['completado' => 0, 'comments' => 'Working on it']); // 'working_on_it' (feature: pipeline-working-on-it-status.md)

  $year = ['mode' => 'year', 'year' => 2099];
  $noF  = ['quoteId' => '', 'channel' => '', 'emailCode' => '', 'statuses' => [], 'bidType' => '', 'user' => ''];

  $all = PipelineTableRepository::getPage($c, $year, $noF, 0);
  check('table: 4 quotes in 2099', 4, $all['total']);

  $byId = [];
  foreach ($all['rows'] as $r) $byId[$r['id']] = $r;
  check('row carries created date', true, (bool)preg_match('#^\d{2}/\d{2}/\d{4}$#', $byId[$qBid]['created']));
  check('row status label present', 'Award', $byId[$qAward]['statusLabel']);
  check('row has no watched field (Quote Watchers removed)', false, isset($byId[$qBid]['watched']));

  // status filter
  $awardOnly = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['statuses' => ['award']]), 0);
  check('status filter award -> 1', 1, $awardOnly['total']);

  // "Working on it" status filter (feature: pipeline-working-on-it-status.md)
  $woiOnly = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['statuses' => ['working_on_it']]), 0);
  check('status filter working_on_it -> 1', 1, $woiOnly['total']);
  check('status filter working_on_it -> the right row', $qWoi, $woiOnly['rows'][0]['id'] ?? null);

  // designated-user filter (qAward is assigned to userB)
  $userFilter = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['user' => $userB]), 0);
  check('designated-user filter -> 1', 1, $userFilter['total']);

  // custom range covering June 2099
  $custom = PipelineTableRepository::getPage($c, ['mode' => 'custom', 'from' => '2099-01-01', 'to' => '2099-12-31'], $noF, 0);
  check('custom range covering the rows -> 4', 4, $custom['total']);
  // inverted range -> empty, no error
  $inverted = PipelineTableRepository::getPage($c, ['mode' => 'custom', 'from' => '2099-12-31', 'to' => '2099-01-01'], $noF, 0);
  check('inverted custom range -> 0', 0, $inverted['total']);

  // distinct channels helper (used by the Channel filter dropdown)
  $channels = PipelineTableRepository::getDistinctChannels($c);
  $values = array_column($channels, 'value');
  check('distinct channels includes our test channel', true, in_array('FedBid', $values, true));

  // --- Internal Due Date filter (feature: internal-due-date.md) ---
  $today    = $mkRfq(['internal_due_date' => date('Y-m-d')]);
  $tomorrow = $mkRfq(['internal_due_date' => date('Y-m-d', strtotime('+1 day'))]);
  $inFive   = $mkRfq(['internal_due_date' => date('Y-m-d', strtotime('+5 day'))]);
  $overdue  = $mkRfq(['internal_due_date' => date('Y-m-d', strtotime('-2 day'))]);
  $noDue    = $mkRfq(['internal_due_date' => null]);

  $dToday = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['dueDate' => 'today']), 0);
  check('dueDate=today -> 1', 1, $dToday['total']);
  $dTomorrow = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['dueDate' => 'tomorrow']), 0);
  check('dueDate=tomorrow -> 1', 1, $dTomorrow['total']);
  $dWeek = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['dueDate' => 'week']), 0);
  check('dueDate=week (rolling 0-7 days, incl. today/tomorrow) -> 3', 3, $dWeek['total']);
  $dOverdue = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['dueDate' => 'overdue']), 0);
  check('dueDate=overdue -> 1', 1, $dOverdue['total']);

  // AND-combines with the period: same due date, wrong year -> 0 rows
  $dWrongYear = PipelineTableRepository::getPage($c, ['mode' => 'year', 'year' => 2098], array_merge($noF, ['dueDate' => 'today']), 0);
  check('dueDate=today + non-matching period -> 0 (AND-combined)', 0, $dWrongYear['total']);

  // row output carries the two date columns (feature: end-date-pipeline-table.md)
  $byId2 = [];
  foreach ($dToday['rows'] as $r) $byId2[$r['id']] = $r;
  check('row carries internalDueDate as m/d/Y', date('m/d/Y'), $byId2[$today]['internalDueDate']);
  check('row with no internal_due_date shows em dash', '—', array_column($all['rows'], 'internalDueDate', 'id')[$qBid] ?? null);
  check('row carries endDate as the raw stored string', true, isset($byId2[$today]['endDate']));

  // --- End Date Range filter (feature: pipeline-table-end-date-range-filter.md) ---
  $eMar10 = $mkRfq(['end_date' => '03/10/2099 09:00']);
  $eMar20 = $mkRfq(['end_date' => '03/20/2099 09:00']);
  $eApr01 = $mkRfq(['end_date' => '04/01/2099 09:00']);
  $eNone  = $mkRfq(['end_date' => '']);

  $eFrom = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['endDateFrom' => '2099-03-15']), 0);
  check('endDateFrom=2099-03-15 -> 2 (Mar 20 + Apr 01, on/after)', 2, $eFrom['total']);

  $eTo = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['endDateTo' => '2099-03-15']), 0);
  check('endDateTo=2099-03-15 -> 1 (Mar 10, on/before)', 1, $eTo['total']);

  $eBoth = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['endDateFrom' => '2099-03-01', 'endDateTo' => '2099-03-31']), 0);
  check('endDateFrom+To spanning March -> 2 (Mar 10 + Mar 20, inclusive)', 2, $eBoth['total']);

  $byIdEnd = [];
  foreach ($eBoth['rows'] as $r) $byIdEnd[$r['id']] = $r;
  check('row with no end_date excluded once End Date range is active', false, isset($byIdEnd[$eNone]));

  $eInverted = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['endDateFrom' => '2099-04-01', 'endDateTo' => '2099-03-01']), 0);
  check('inverted end date range -> 0, no error', 0, $eInverted['total']);

  // AND-combines with the period: same end date, wrong year -> 0 rows
  $eWrongYear = PipelineTableRepository::getPage($c, ['mode' => 'year', 'year' => 2098], array_merge($noF, ['endDateFrom' => '2099-03-01']), 0);
  check('endDateFrom + non-matching period -> 0 (AND-combined)', 0, $eWrongYear['total']);

  // row output still carries the raw MM/DD/YYYY HH:mm string (End Date column unchanged)
  $eFromById = [];
  foreach ($eFrom['rows'] as $r) $eFromById[$r['id']] = $r;
  check('row carries endDate as the raw stored string', '03/20/2099 09:00', $eFromById[$eMar20]['endDate']);

  // --- Submitted Date filter (feature: pipeline-table-submitted-date-filter.md) ---
  $sMar10 = $mkRfq(['fecha_submitted' => '2099-03-10']);
  $sMar20 = $mkRfq(['fecha_submitted' => '2099-03-20']);
  $sApr01 = $mkRfq(['fecha_submitted' => '2099-04-01']);
  $sNone  = $mkRfq(['fecha_submitted' => null]);

  $sFrom = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['submittedFrom' => '2099-03-15']), 0);
  check('submittedFrom=2099-03-15 -> 2 (Mar 20 + Apr 01, on/after)', 2, $sFrom['total']);

  $sTo = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['submittedTo' => '2099-03-15']), 0);
  check('submittedTo=2099-03-15 -> 1 (Mar 10, on/before)', 1, $sTo['total']);

  $sBoth = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['submittedFrom' => '2099-03-01', 'submittedTo' => '2099-03-31']), 0);
  check('submittedFrom+To spanning March -> 2 (Mar 10 + Mar 20, inclusive)', 2, $sBoth['total']);

  $byIdSubmitted = [];
  foreach ($sBoth['rows'] as $r) $byIdSubmitted[$r['id']] = $r;
  check('never-submitted row excluded when Submitted Date filter is active', false, isset($byIdSubmitted[$sNone]));

  $sInverted = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['submittedFrom' => '2099-04-01', 'submittedTo' => '2099-03-01']), 0);
  check('inverted submitted range -> 0, no error', 0, $sInverted['total']);

  // AND-combines with the period: same submitted date, wrong year -> 0 rows
  $sWrongYear = PipelineTableRepository::getPage($c, ['mode' => 'year', 'year' => 2098], array_merge($noF, ['submittedFrom' => '2099-03-01']), 0);
  check('submittedFrom + non-matching period -> 0 (AND-combined)', 0, $sWrongYear['total']);

  // row output carries the formatted 'submitted' field
  $sFromById = [];
  foreach ($sFrom['rows'] as $r) $sFromById[$r['id']] = $r;
  check('row carries submitted as m/d/Y', '03/20/2099', $sFromById[$sMar20]['submitted']);
  check('row with no fecha_submitted shows em dash', '—', array_column($all['rows'], 'submitted', 'id')[$qBid] ?? null);

  // --- Type of Contract filter + column (feature: pipeline-type-of-contract.md) ---
  $ctVar  = $mkRfq(['type_of_contract' => 'RFQ - VAR']);
  $ctProf = $mkRfq(['type_of_contract' => 'Professional Services']);
  $ctNone = $mkRfq(['type_of_contract' => null]);

  $ctFilter = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['contractType' => 'RFQ - VAR']), 0);
  $ctFilterIds = array_column($ctFilter['rows'], 'id');
  check('contractType filter -> only the RFQ - VAR row', [$ctVar], $ctFilterIds);

  $ctUncatFilter = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['contractType' => 'Uncategorized']), 0);
  $ctUncatIds = array_column($ctUncatFilter['rows'], 'id');
  check('contractType=Uncategorized filter includes the blank row', true, in_array($ctNone, $ctUncatIds, true));
  check('contractType=Uncategorized filter excludes the RFQ - VAR row', false, in_array($ctVar, $ctUncatIds, true));

  $byIdCt = [];
  foreach ($all['rows'] as $r) $byIdCt[$r['id']] = $r;
  $ctAll = PipelineTableRepository::getPage($c, $year, $noF, 0);
  $byIdCtAll = [];
  foreach ($ctAll['rows'] as $r) $byIdCtAll[$r['id']] = $r;
  check('row carries typeOfContract label', 'RFQ - VAR', $byIdCtAll[$ctVar]['typeOfContract'] ?? null);
  check('row with no type_of_contract shows Uncategorized', 'Uncategorized', $byIdCtAll[$ctNone]['typeOfContract'] ?? null);

  // AND-combines with another filter (bidType + contractType together narrows further)
  $ctAndBid = PipelineTableRepository::getPage($c, $year, array_merge($noF, ['contractType' => 'RFQ - VAR', 'bidType' => 'IT']), 0);
  check('contractType + bidType AND-combine -> still just the one matching row', 1, $ctAndBid['total']);

  // --- Sort by Internal Due Date / End Date (bugs/pipeline-table-due-date-sort.md) ---
  // Own cohort (2097) so only these rows are in the period.
  $y97 = ['mode' => 'year', 'year' => 2097];
  $at97 = ['created_at' => '2097-05-01 10:00:00'];
  $sMid    = $mkRfq($at97 + ['internal_due_date' => '2097-03-15', 'end_date' => '03/15/2097 09:00']);
  $sLate   = $mkRfq($at97 + ['internal_due_date' => '2097-09-01', 'end_date' => '09/01/2097 17:30']);
  $sBlank  = $mkRfq($at97 + ['internal_due_date' => null,         'end_date' => '']);
  $sEarly  = $mkRfq($at97 + ['internal_due_date' => '2097-01-02', 'end_date' => '01/02/2097 08:00']);
  $sLegacy = $mkRfq($at97 + ['internal_due_date' => null,         'end_date' => '06/01/2097']); // no time part
  // Same-day end dates: time-of-day orders them, not the string.
  $sSameAm = $mkRfq($at97 + ['internal_due_date' => '2097-03-15', 'end_date' => '12/31/2096 08:00']);
  $sSamePm = $mkRfq($at97 + ['internal_due_date' => null,         'end_date' => '12/31/2096 18:00']);

  $ids = function ($sort) use ($c, $y97, $noF) {
    return array_column(PipelineTableRepository::getPage($c, $y97, $noF, 0, $sort)['rows'], 'id');
  };

  check('default order (no sort) is newest quote first',
    [$sSamePm, $sSameAm, $sLegacy, $sEarly, $sBlank, $sLate, $sMid], $ids([]));
  check('unknown sort column falls back to newest quote first',
    [$sSamePm, $sSameAm, $sLegacy, $sEarly, $sBlank, $sLate, $sMid], $ids(['col' => 'id; DROP TABLE rfq', 'dir' => 'asc']));

  // Internal Due Date: equal dates tie-break on id DESC; undated rows last in both directions.
  check('internalDueDate asc: oldest -> newest, blanks last',
    [$sEarly, $sSameAm, $sMid, $sLate, $sSamePm, $sLegacy, $sBlank], $ids(['col' => 'internalDueDate', 'dir' => 'asc']));
  check('internalDueDate desc: newest -> oldest, blanks still last',
    [$sLate, $sSameAm, $sMid, $sEarly, $sSamePm, $sLegacy, $sBlank], $ids(['col' => 'internalDueDate', 'dir' => 'desc']));
  check('invalid dir is treated as asc',
    $ids(['col' => 'internalDueDate', 'dir' => 'asc']), $ids(['col' => 'internalDueDate', 'dir' => 'sideways']));

  // End Date: parsed as a datetime, not compared as an MM/DD/YYYY string. A legacy value with
  // no time part still parses (MySQL STR_TO_DATE reads it as midnight) and sorts by its date
  // (pinned behaviour).
  check('endDate asc: oldest -> newest by real date+time, legacy date-only in place, blanks last',
    [$sSameAm, $sSamePm, $sEarly, $sMid, $sLegacy, $sLate, $sBlank], $ids(['col' => 'endDate', 'dir' => 'asc']));
  check('endDate desc: newest -> oldest, blanks still last',
    [$sLate, $sLegacy, $sMid, $sEarly, $sSamePm, $sSameAm, $sBlank], $ids(['col' => 'endDate', 'dir' => 'desc']));

  // Sort spans every page, not just the visible 25: push the earliest date onto what would be page 2.
  for ($i = 0; $i < PipelineTableRepository::PAGE_SIZE; $i++) $mkRfq($at97 + ['internal_due_date' => '2097-06-01']);
  $p0 = PipelineTableRepository::getPage($c, $y97, $noF, 0, ['col' => 'internalDueDate', 'dir' => 'asc']);
  check('sort applies across pages: earliest due date is first on page 1', $sEarly, $p0['rows'][0]['id'] ?? null);
  $p1 = PipelineTableRepository::getPage($c, $y97, $noF, 1, ['col' => 'internalDueDate', 'dir' => 'asc']);
  check('sort applies across pages: blanks land at the end of the last page', $sBlank, end($p1['rows'])['id'] ?? null);

} finally {
  $c->rollBack();
  Conexion::cerrar_conexion();
}

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
