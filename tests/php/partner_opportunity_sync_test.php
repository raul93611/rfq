<?php
/**
 * Integration test for Partner Opportunity Sync.
 *
 * Covers (acceptance criteria numbers from features/partner-opportunity-sync.md):
 *   1  token auth (missing / wrong / revoked → 401, valid → 200)
 *   2  sharing gate + Solicitation # validation
 *   3  per-file sharing (generated files never shareable, non-shared id → 404)
 *   4  changed-since: changed + removed tombstones, stable order, pagination
 *   5  webhook signature + timestamp window
 *   6  incoming new record → quote (service-user creator, assignee, pinned id, audit)
 *   7  incoming matching exactly one quote → link, shared fields only, no duplicate
 *   8  incoming matching several quotes → Needs review (ambiguous)
 *   8b incoming equal to an unkeyed quote's Code → Needs review (possible), resolvable
 *   9  conflict: older ignored, newer applied
 *   10 no echo on applied incoming change
 *   11 one notice per shared-field change; unrelated edits enqueue none
 *   12 retry back-off then failed; Retry re-queues
 *   13 unshare/delete → removed notice; incoming removed hides only untouched quotes
 *   14 statuses after Award never sent, ignored inbound
 *   15 unmatched assignee → default + needs-assignment; missing default → rejected, never dropped
 *
 * Transaction-isolated: everything runs inside a ROLLBACK, so no row survives. No DDL runs here —
 * apply sql/partner_opportunity_sync_migration.sql first.
 * Run:  docker exec lamp-php83 php /var/www/html/rfq/tests/php/partner_opportunity_sync_test.php
 */

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/routes.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Quote/Rfq.inc.php';
require_once $root . 'app/Quote/RepositorioRfq.inc.php';
require_once $root . 'app/Quote/AuditTrail.inc.php';
require_once $root . 'app/Quote/AuditTrailRepository.inc.php';
require_once $root . 'app/Utilities/Input.inc.php';
foreach (['PartnerIntegrationRepository', 'PartnerShareRepository', 'PartnerDocumentRepository',
          'PartnerOutboxRepository', 'PartnerSyncService', 'PartnerApi'] as $cls) {
  require_once $root . "app/Partner/$cls.inc.php";
}

$pass = 0; $fail = 0;
function check($label, $expected, $actual) {
  global $pass, $fail;
  if ($expected === $actual) { $pass++; echo "  PASS  $label\n"; }
  else { $fail++; echo "  FAIL  $label — expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n"; }
}

Conexion::abrir_conexion();
$c = Conexion::obtener_conexion();
$c->beginTransaction();

$run = strtoupper(substr(md5(uniqid('', true)), 0, 6));
$q = function ($sql, array $p = []) use ($c) { $s = $c->prepare($sql); $s->execute($p); return $s; };
$one = function ($sql, array $p = []) use ($q) { return $q($sql, $p)->fetch(PDO::FETCH_ASSOC); };
$val = function ($sql, array $p = []) use ($q) { return $q($sql, $p)->fetchColumn(); };

function mkQuote($c, array $o = []) {
  $d = array_merge([
    'id_usuario' => 1, 'usuario_designado' => 1, 'canal' => 'FBO', 'email_code' => 'T-' . uniqid(),
    'type_of_bid' => 'Services', 'issue_date' => '', 'end_date' => '', 'status' => 0, 'completado' => 0,
    'total_cost' => 0, 'total_price' => 0, 'comments' => 'No comments', 'award' => 0, 'deleted' => 0,
    'name' => 'Test quote', 'client' => 'Test Agency',
  ], $o);
  $cols = implode(',', array_keys($d));
  $ph = implode(',', array_map(function ($k) { return ':' . $k; }, array_keys($d)));
  $s = $c->prepare("INSERT INTO rfq ($cols) VALUES ($ph)");
  $s->execute($d);
  return (int)$c->lastInsertId();
}
function quoteRow($c, $id) { $s = $c->prepare('SELECT * FROM rfq WHERE id = :id'); $s->execute([':id' => $id]); return $s->fetch(PDO::FETCH_ASSOC); }
function outboxCount($c, $id = null, $event = null) {
  $sql = 'SELECT COUNT(*) FROM partner_webhook_outbox WHERE 1=1'; $p = [];
  if ($id !== null) { $sql .= ' AND id_rfq = :i'; $p[':i'] = $id; }
  if ($event !== null) { $sql .= ' AND event = :e'; $p[':e'] = $event; }
  $s = $c->prepare($sql); $s->execute($p); return (int)$s->fetchColumn();
}
function incoming(array $o = []) {
  return array_merge([
    'record_id' => 'P-' . uniqid(), 'solicitation_number' => 'SOL-' . uniqid(), 'name' => 'Partner opportunity',
    'agency' => 'Dept of Tests', 'issue_date' => '2026-10-01T04:00:00Z', 'end_date' => '2026-10-31T21:00:00Z',
    'status' => null, 'assignee_email' => null, 'shared_updated_at' => '2026-10-14T12:00:00Z',
    'removed' => false, 'files' => [],
  ], $o);
}

try {
  $now = time();
  $serviceUserId = (int)$val("SELECT id FROM usuarios WHERE nombre_usuario = 'partner_integration'");
  check('service user seeded and disabled', 0, (int)$val('SELECT status FROM usuarios WHERE id = :i', [':i' => $serviceUserId]));
  // config.inc.php is gitignored: the constant must be added per environment (see DEPLOYMENT.md).
  check('PARTNER_DEFAULT_ASSIGNEE_EMAIL is configured', true, defined('PARTNER_DEFAULT_ASSIGNEE_EMAIL'));
  $adminId = (int)$val("SELECT id FROM usuarios WHERE email = :e", [':e' => defined('PARTNER_DEFAULT_ASSIGNEE_EMAIL') ? PARTNER_DEFAULT_ASSIGNEE_EMAIL : '']);
  check('default assignee exists in usuarios', true, $adminId > 0);

  $tmpDocs = sys_get_temp_dir() . '/partner_test_' . uniqid();
  mkdir($tmpDocs, 0777, true);
  PartnerDocumentRepository::$baseDir = $tmpDocs;
  PartnerSyncService::$defaultAssigneeEmail = null;

  // ---------------------------------------------------------------- 1. auth
  echo "-- 1. token auth\n";
  PartnerIntegrationRepository::saveSettings($c, [
    'enabled' => 1, 'partner_base_url' => 'https://api.partner.test/v2',
    'partner_webhook_url' => 'https://hooks.partner.test/elogic', 'partner_token' => 'ptk_test', 'signing_secret' => 'whsec_test',
  ]);
  $token = PartnerIntegrationRepository::issueToken($c, $adminId);
  check('issued token has prefix', true, strpos($token, 'elg_live_') === 0);
  $row = PartnerIntegrationRepository::get($c);
  check('token stored hashed, not plaintext', hash('sha256', $token), $row['issued_token_hash']);
  check('last4 kept for masking', substr($token, -4), $row['issued_token_last4']);
  check('valid bearer accepted', true, PartnerIntegrationRepository::verifyBearer($c, "Bearer $token"));
  check('missing header rejected', false, PartnerIntegrationRepository::verifyBearer($c, null));
  check('wrong token rejected', false, PartnerIntegrationRepository::verifyBearer($c, 'Bearer elg_live_nope'));
  $auth = ['authorization' => "Bearer $token"];
  $r = PartnerApi::handle($c, 'GET', ['partner', 'opportunities'], [], [], '', $now);
  check('no token → bare 401', 401, $r['status']);
  check('401 body leaks nothing', null, $r['json']);
  $r = PartnerApi::handle($c, 'GET', ['partner', 'opportunities'], [], $auth, '', $now);
  check('valid token → 200', 200, $r['status']);
  $newToken = PartnerIntegrationRepository::issueToken($c, $adminId); // rotate
  check('rotated: old token stops working', false, PartnerIntegrationRepository::verifyBearer($c, "Bearer $token"));
  check('rotated: new token works', true, PartnerIntegrationRepository::verifyBearer($c, "Bearer $newToken"));
  $auth = ['authorization' => "Bearer $newToken"];
  PartnerIntegrationRepository::revokeToken($c);
  check('revoked token → 401', 401, PartnerApi::handle($c, 'GET', ['partner', 'opportunities'], [], $auth, '', $now)['status']);
  $newToken = PartnerIntegrationRepository::issueToken($c, $adminId);
  $auth = ['authorization' => "Bearer $newToken"];
  PartnerIntegrationRepository::saveSettings($c, ['enabled' => 0]);
  check('integration disabled → 401', 401, PartnerApi::handle($c, 'GET', ['partner', 'opportunities'], [], $auth, '', $now)['status']);
  PartnerIntegrationRepository::saveSettings($c, ['enabled' => 1]);

  // ---------------------------------------------------------------- 2. sharing gate + validation
  echo "-- 2. sharing gate + validation\n";
  check('normalizeKey trims, uppercases, collapses spaces', 'FA8307-26-Q-0021 X', PartnerShareRepository::normalizeKey("  fa8307-26-q-0021   x "));
  check('normalizeKey blank → null', null, PartnerShareRepository::normalizeKey('   '));
  check('sharing on + blank number rejected', true, PartnerShareRepository::validateSolicitation($c, true, '  ', null) !== null);
  check('sharing off + blank number ok', null, PartnerShareRepository::validateSolicitation($c, false, '', null));
  $sol1 = "ZZ-$run-0001";
  $shared = mkQuote($c, ['name' => 'Shared one']);
  PartnerShareRepository::saveShareSettings($c, $shared, true, $sol1);
  $sr = quoteRow($c, $shared);
  check('share flag saved', 1, (int)$sr['share_with_partner']);
  check('key normalized + saved', "ZZ-$run-0001", $sr['solicitation_key']);
  check('duplicate key (case/space-insensitive) rejected', true, PartnerShareRepository::validateSolicitation($c, true, strtolower($sol1), null) !== null);
  check('same quote may re-save its own number', null, PartnerShareRepository::validateSolicitation($c, true, $sol1, $shared));
  $deletedDup = mkQuote($c, ['deleted' => 1]);
  PartnerShareRepository::saveShareSettings($c, $deletedDup, false, "ZZ-$run-DEL");
  check('deleted quotes do not block a key', null, PartnerShareRepository::validateSolicitation($c, true, "ZZ-$run-DEL", null));
  $unshared = mkQuote($c, ['name' => 'Unshared one']);
  PartnerShareRepository::saveShareSettings($c, $unshared, false, "ZZ-$run-0002");
  PartnerSyncService::reconcile($c, $shared);
  PartnerSyncService::reconcile($c, $unshared);
  $list = PartnerApi::handle($c, 'GET', ['partner', 'opportunities'], ['changed_since' => '2000-01-01T00:00:00Z'], $auth, '', $now)['json'];
  $nums = array_column($list['data'], 'solicitation_number');
  check('shared quote returned', true, in_array($sol1, $nums, true));
  check('unshared quote never returned', false, in_array("ZZ-$run-0002", $nums, true));

  // ---------------------------------------------------------------- 3. files
  echo "-- 3. per-file sharing\n";
  mkdir("$tmpDocs/$shared", 0777, true);
  file_put_contents("$tmpDocs/$shared/Statement_of_Work.docx", 'SOW-BYTES');
  file_put_contents("$tmpDocs/$shared/Internal_Notes.pdf", 'NOTES');
  file_put_contents("$tmpDocs/$shared/ABC-123(proposal).pdf", 'PROPOSAL');
  file_put_contents("$tmpDocs/$shared/ABC-123(checklist).pdf", 'CHECKLIST');
  check('proposal pdf classified generated', true, PartnerDocumentRepository::isGenerated('ABC-123(proposal).pdf'));
  check('checklist pdf classified generated', true, PartnerDocumentRepository::isGenerated('ABC-123(checklist).pdf'));
  check('re-quote pdf classified generated', true, PartnerDocumentRepository::isGenerated('X(re_quote_items_table).pdf'));
  check('tracking pdf classified generated', true, PartnerDocumentRepository::isGenerated('X(trackings).pdf'));
  check('uploaded file not generated', false, PartnerDocumentRepository::isGenerated('Statement_of_Work.docx'));
  PartnerDocumentRepository::syncFolder($c, $shared);
  check('rows appear lazily with shared = 0', 0, (int)$val('SELECT COUNT(*) FROM partner_documents WHERE id_rfq = :i AND shared = 1', [':i' => $shared]));
  check('generated file rejected server-side', false, PartnerDocumentRepository::setShared($c, $shared, 'ABC-123(proposal).pdf', true));
  check('uploaded file can be shared', true, PartnerDocumentRepository::setShared($c, $shared, 'Statement_of_Work.docx', true));
  check('share enqueues exactly one document.shared', 1, outboxCount($c, $shared, 'document.shared'));
  PartnerDocumentRepository::setShared($c, $shared, 'Statement_of_Work.docx', true);
  check('re-ticking an already shared file enqueues nothing more', 1, outboxCount($c, $shared, 'document.shared'));
  $opp = PartnerApi::handle($c, 'GET', ['partner', 'opportunities', $sol1], [], $auth, '', $now);
  check('single opportunity 200', 200, $opp['status']);
  $files = $opp['json']['files'];
  check('only shared file listed', ['Statement_of_Work.docx'], array_column($files, 'name'));
  check('file entry exposes sha256', hash('sha256', 'SOW-BYTES'), $files[0]['sha256']);
  check('file entry exposes size', 9, $files[0]['size']);
  $fid = $files[0]['id'];
  $dl = PartnerApi::handle($c, 'GET', ['partner', 'files', (string)$fid], [], $auth, '', $now);
  check('shared file downloadable', 200, $dl['status']);
  check('download resolves to the stored file', "$tmpDocs/$shared/Statement_of_Work.docx", $dl['file']);
  $notSharedId = (int)$val("SELECT id FROM partner_documents WHERE id_rfq = :i AND file_name = 'Internal_Notes.pdf'", [':i' => $shared]);
  check('non-shared file id → 404', 404, PartnerApi::handle($c, 'GET', ['partner', 'files', (string)$notSharedId], [], $auth, '', $now)['status']);
  check('generated file id → 404', 404, PartnerApi::handle($c, 'GET', ['partner', 'files',
    (string)$val("SELECT id FROM partner_documents WHERE id_rfq = :i AND file_name = 'ABC-123(proposal).pdf'", [':i' => $shared])], [], $auth, '', $now)['status']);
  check('unknown file id → 404', 404, PartnerApi::handle($c, 'GET', ['partner', 'files', '99999999'], [], $auth, '', $now)['status']);
  check('files endpoint needs the token', 401, PartnerApi::handle($c, 'GET', ['partner', 'files', (string)$fid], [], [], '', $now)['status']);

  // ---------------------------------------------------------------- 11. change detection (needs a baseline)
  echo "-- 11. change detection\n";
  $base = outboxCount($c, $shared, 'opportunity.updated');
  check('first reconcile of a shared quote queued one notice', 1, $base);
  PartnerSyncService::reconcile($c, $shared);
  check('re-reconcile with no change enqueues nothing', $base, outboxCount($c, $shared, 'opportunity.updated'));
  $before = quoteRow($c, $shared);
  $q('UPDATE rfq SET total_price = 1234.5, total_cost = 1000, taxes = 3 WHERE id = :i', [':i' => $shared]);
  PartnerSyncService::reconcile($c, $shared);
  check('pricing edit enqueues nothing', $base, outboxCount($c, $shared, 'opportunity.updated'));
  check('pricing edit does not move shared timestamp', $before['partner_shared_updated_at'], quoteRow($c, $shared)['partner_shared_updated_at']);
  check('pricing edit does not change the payload hash', $before['partner_payload_hash'], quoteRow($c, $shared)['partner_payload_hash']);
  $q("UPDATE rfq SET name = 'Shared one — revised' WHERE id = :i", [':i' => $shared]);
  // a pending unsent notice for the quote coalesces: deliver the first one so the new one is distinct
  $q('UPDATE partner_webhook_outbox SET delivered_at = :d WHERE id_rfq = :i', [':d' => gmdate('Y-m-d H:i:s'), ':i' => $shared]);
  PartnerSyncService::reconcile($c, $shared);
  check('shared-field change enqueues exactly one notice', $base + 1, outboxCount($c, $shared, 'opportunity.updated'));
  PartnerSyncService::reconcile($c, $shared);
  check('…and only one on repeat', $base + 1, outboxCount($c, $shared, 'opportunity.updated'));
  check('payload hash moved with the shared field', true, quoteRow($c, $shared)['partner_payload_hash'] !== $before['partner_payload_hash']);

  // ---------------------------------------------------------------- 11b. newest notice supersedes a waiting one
  echo "-- 11b. newest notice wins\n";
  $flip = mkQuote($c, ['name' => 'Flip flop']);
  $pending = function ($id) use ($c) {
    $st = $c->prepare('SELECT event FROM partner_webhook_outbox WHERE id_rfq = :i AND delivered_at IS NULL AND failed_at IS NULL ORDER BY id');
    $st->execute([':i' => $id]);
    return $st->fetchAll(PDO::FETCH_COLUMN);
  };
  PartnerShareRepository::saveShareSettings($c, $flip, true, "FLIP-$run");
  PartnerSyncService::reconcile($c, $flip);
  check('share queues one update', ['opportunity.updated'], $pending($flip));
  PartnerShareRepository::saveShareSettings($c, $flip, false, "FLIP-$run");
  PartnerSyncService::reconcile($c, $flip);
  check('unshare supersedes the waiting update', ['opportunity.removed'], $pending($flip));
  PartnerShareRepository::saveShareSettings($c, $flip, true, "FLIP-$run");
  PartnerSyncService::reconcile($c, $flip);
  check('re-share supersedes the waiting removal — no stale "removed" is left to be delivered last', ['opportunity.updated'], $pending($flip));
  mkdir("$tmpDocs/$flip", 0777, true);
  file_put_contents("$tmpDocs/$flip/Plan.pdf", 'PLAN');
  PartnerDocumentRepository::setShared($c, $flip, 'Plan.pdf', true);
  check('file share queues a document notice after the update', ['opportunity.updated', 'document.shared'], $pending($flip));
  PartnerDocumentRepository::setShared($c, $flip, 'Plan.pdf', false);
  check('file unshare supersedes the waiting file share, other family untouched', ['opportunity.updated', 'document.removed'], $pending($flip));

  // ---------------------------------------------------------------- 5. webhook
  echo "-- 5. webhook signature\n";
  $secret = 'whsec_test';
  $sign = function ($body, $ts) use ($secret) { return hash_hmac('sha256', $ts . '.' . $body, $secret); };
  $evBody = json_encode(['event' => 'opportunity.removed', 'record_id' => 'P-NOPE', 'solicitation_number' => 'NOPE']);
  $ts = (string)$now;
  $hdrs = ['authorization' => "Bearer $newToken", 'x-timestamp' => $ts, 'x-signature' => $sign($evBody, $ts)];
  $reviewBefore = (int)$val('SELECT COUNT(*) FROM partner_review');
  check('valid signed webhook → 200', 200, PartnerApi::handle($c, 'POST', ['partner', 'webhook'], [], $hdrs, $evBody, $now)['status']);
  $bad = $hdrs; $bad['x-signature'] = str_repeat('0', 64);
  check('bad signature → 401', 401, PartnerApi::handle($c, 'POST', ['partner', 'webhook'], [], $bad, $evBody, $now)['status']);
  $stale = (string)($now - 301);
  $old = ['authorization' => "Bearer $newToken", 'x-timestamp' => $stale, 'x-signature' => $sign($evBody, $stale)];
  check('stale timestamp (>5 min) → 401', 401, PartnerApi::handle($c, 'POST', ['partner', 'webhook'], [], $old, $evBody, $now)['status']);
  $noTok = $hdrs; unset($noTok['authorization']);
  check('missing bearer on webhook → 401', 401, PartnerApi::handle($c, 'POST', ['partner', 'webhook'], [], $noTok, $evBody, $now)['status']);
  check('rejected webhooks changed nothing', $reviewBefore, (int)$val('SELECT COUNT(*) FROM partner_review'));

  // ---------------------------------------------------------------- 6. incoming → new quote
  echo "-- 6. incoming new record\n";
  $adminEmail = (string)$val('SELECT email FROM usuarios WHERE id = :i', [':i' => $adminId]);
  $outBefore = outboxCount($c);
  $rec = incoming(['solicitation_number' => "NEW-$run-1", 'name' => 'Brand new from partner', 'assignee_email' => $adminEmail]);
  $res = PartnerSyncService::applyIncoming($c, $rec);
  check('outcome created', 'created', $res['outcome']);
  $nq = quoteRow($c, $res['id_rfq']);
  check('creator is the Partner Integration user', $serviceUserId, (int)$nq['id_usuario']);
  check('designated user matched by email', $adminId, (int)$nq['usuario_designado']);
  check('needs-assignment not set when matched', 0, (int)$nq['partner_needs_assignment']);
  check('share_with_partner = 1', 1, (int)$nq['share_with_partner']);
  check('partner_record_id pinned', $rec['record_id'], $nq['partner_record_id']);
  check('key stored', "NEW-$run-1", $nq['solicitation_key']);
  check('name applied', 'Brand new from partner', $nq['name']);
  check('shared timestamp adopted from partner (UTC)', '2026-10-14 12:00:00', $nq['partner_shared_updated_at']);
  check('audit event partner_sync_in written', 1, (int)$val("SELECT COUNT(*) FROM audit_trails WHERE id_rfq = :i AND action_type = 'partner_sync_in'", [':i' => $res['id_rfq']]));
  check('audit shows the integration user, not a session', 'Partner Integration', $val("SELECT username FROM audit_trails WHERE id_rfq = :i AND action_type = 'partner_sync_in' LIMIT 1", [':i' => $res['id_rfq']]));
  // ---- 10. no echo
  check('applying incoming enqueued nothing (no echo)', $outBefore, outboxCount($c));
  PartnerSyncService::reconcile($c, $res['id_rfq']);
  check('…and a following reconcile still enqueues nothing', $outBefore, outboxCount($c));

  // ---------------------------------------------------------------- 6b. incoming files
  echo "-- 6b. incoming files\n";
  $fileCalls = [];
  PartnerSyncService::$http = function ($method, $url, $headers, $body, $timeout) use (&$fileCalls) {
    $fileCalls[] = ['url' => $url, 'auth' => $headers['Authorization'] ?? null];
    return ['status' => 200, 'body' => 'AMENDMENT-0001-BYTES', 'error' => null];
  };
  $good = hash('sha256', 'AMENDMENT-0001-BYTES');
  $recF = incoming(['solicitation_number' => "FILES-$run", 'files' => [
    ['id' => 'F1', 'name' => 'Amendment 0001.pdf', 'size' => 20, 'sha256' => $good, 'download_url' => 'https://api.partner.test/v2/files/F1'],
    ['id' => 'F2', 'name' => 'Elsewhere.pdf', 'sha256' => $good, 'download_url' => 'https://evil.example/steal'],
    ['id' => 'F3', 'name' => 'Tampered.pdf', 'sha256' => str_repeat('a', 64), 'download_url' => 'https://api.partner.test/v2/files/F3'],
    ['id' => 'F4', 'name' => '../../escape.pdf', 'sha256' => $good, 'download_url' => 'https://api.partner.test/v2/files/F4'],
  ]]);
  $resF = PartnerSyncService::applyIncoming($c, $recF);
  $fid = $resF['id_rfq'];
  $dirF = "$tmpDocs/$fid";
  check('partner file downloaded under a sanitized name', 'AMENDMENT-0001-BYTES', @file_get_contents("$dirF/Amendment_0001.pdf"));
  check('partner credential sent only to the partner host', ['https://api.partner.test/v2/files/F1', 'https://api.partner.test/v2/files/F3', 'https://api.partner.test/v2/files/F4'], array_column($fileCalls, 'url'));
  check('foreign-host file never fetched or stored', false, file_exists("$dirF/Elsewhere.pdf"));
  check('hash mismatch is not stored', false, file_exists("$dirF/Tampered.pdf"));
  check('path traversal name stays inside the quote folder', false, file_exists("$tmpDocs/escape.pdf") || file_exists("$tmpDocs/../escape.pdf"));
  $fd = $one("SELECT * FROM partner_documents WHERE id_rfq = :i AND file_name = 'Amendment_0001.pdf'", [':i' => $fid]);
  check('registered with the partner file id', 'F1', $fd['partner_file_id']);
  check('document audit written', true, (int)$val("SELECT COUNT(*) FROM audit_trails WHERE id_rfq = :i AND audit_trail LIKE '%Amendment_0001.pdf%'", [':i' => $fid]) > 0);
  check('partner-origin file is never offered back', [], PartnerDocumentRepository::sharedFiles($c, $fid));
  check('partner-origin file cannot be toggled', false, PartnerDocumentRepository::setShared($c, $fid, 'Amendment_0001.pdf', false));
  $fileCalls = [];
  PartnerSyncService::applyIncoming($c, array_merge($recF, ['shared_updated_at' => '2026-10-15T12:00:00Z']));
  check('unchanged partner file is not downloaded again', 0, count(array_filter($fileCalls, function ($x) { return substr($x['url'], -3) === '/F1'; })));
  PartnerSyncService::applyIncoming($c, array_merge($recF, ['shared_updated_at' => '2026-10-16T12:00:00Z', 'files' => null]));
  check('notice without a file list removes nothing', null, $val("SELECT removed_at FROM partner_documents WHERE id = :i", [':i' => $fd['id']]));
  PartnerSyncService::applyIncoming($c, array_merge($recF, ['shared_updated_at' => '2026-10-17T12:00:00Z', 'files' => []]));
  check('full list without the file marks it removed', true, $val("SELECT removed_at FROM partner_documents WHERE id = :i", [':i' => $fd['id']]) !== null);
  check('…but our copy is kept on disk', true, is_file("$dirF/Amendment_0001.pdf"));
  PartnerSyncService::$http = null;

  // ---------------------------------------------------------------- 15. assignee fallback + rejection
  echo "-- 15. assignee fallback\n";
  $rec = incoming(['solicitation_number' => "NEW-$run-2", 'assignee_email' => 'ghost@nowhere.invalid']);
  $res = PartnerSyncService::applyIncoming($c, $rec);
  $nq = quoteRow($c, $res['id_rfq']);
  check('unmatched assignee → default assignee', $adminId, (int)$nq['usuario_designado']);
  check('needs-assignment flagged', 1, (int)$nq['partner_needs_assignment']);
  PartnerSyncService::$defaultAssigneeEmail = 'nobody-' . $run . '@nowhere.invalid';
  $quotesBefore = (int)$val('SELECT COUNT(*) FROM rfq');
  $rec = incoming(['solicitation_number' => "NEW-$run-3"]);
  $res = PartnerSyncService::applyIncoming($c, $rec);
  check('no usable default → rejected', 'rejected', $res['outcome']);
  check('rejected record creates no quote', $quotesBefore, (int)$val('SELECT COUNT(*) FROM rfq'));
  $rv = $one("SELECT * FROM partner_review WHERE partner_record_id = :r AND resolved_at IS NULL", [':r' => $rec['record_id']]);
  check('rejected record is listed, not dropped', 'rejected', $rv['kind']);
  PartnerSyncService::$defaultAssigneeEmail = null;

  // ---------------------------------------------------------------- 7. exactly one match → link
  echo "-- 7. link existing\n";
  $mine = mkQuote($c, ['name' => 'Ours already', 'client' => 'Our Agency', 'total_price' => 777]);
  PartnerShareRepository::saveShareSettings($c, $mine, false, "LINK-$run");
  $quotesBefore = (int)$val('SELECT COUNT(*) FROM rfq');
  $echoBase = outboxCount($c);
  $rec = incoming(['solicitation_number' => strtolower("LINK-$run"), 'name' => 'Partner title', 'agency' => 'Partner Agency']);
  $res = PartnerSyncService::applyIncoming($c, $rec);
  check('outcome linked', 'linked', $res['outcome']);
  check('linking enqueued nothing (no echo)', $echoBase, outboxCount($c));
  check('linked to the existing quote', $mine, $res['id_rfq']);
  check('no duplicate row', $quotesBefore, (int)$val('SELECT COUNT(*) FROM rfq'));
  $lq = quoteRow($c, $mine);
  check('record id pinned', $rec['record_id'], $lq['partner_record_id']);
  check('sharing switched on by the link', 1, (int)$lq['share_with_partner']);
  check('shared fields applied', 'Partner title', $lq['name']);
  check('non-shared fields untouched', 777.0, (float)$lq['total_price']);
  check('partner_link audit written', 1, (int)$val("SELECT COUNT(*) FROM audit_trails WHERE id_rfq = :i AND action_type = 'partner_link'", [':i' => $mine]));
  $res2 = PartnerSyncService::applyIncoming($c, array_merge($rec, ['shared_updated_at' => '2026-10-14T13:00:00Z', 'name' => 'Partner title v2']));
  check('second delivery for pinned record updates in place', 'updated', $res2['outcome']);
  check('updating from partner enqueued nothing (no echo)', $echoBase, outboxCount($c));
  PartnerSyncService::reconcile($c, $mine);
  check('…and the next reconcile still sees no difference', $echoBase, outboxCount($c));
  check('still one row for that record', 1, (int)$val('SELECT COUNT(*) FROM rfq WHERE partner_record_id = :r', [':r' => $rec['record_id']]));
  // pinned link survives a solicitation-number typo fix on our side
  PartnerShareRepository::saveShareSettings($c, $mine, true, "LINK-$run-FIXED");
  $res3 = PartnerSyncService::applyIncoming($c, array_merge($rec, ['shared_updated_at' => '2026-10-14T14:00:00Z', 'name' => 'Partner title v3']));
  check('pinned id still resolves after number edit', $mine, $res3['id_rfq']);

  // ---------------------------------------------------------------- 9. conflict
  echo "-- 9. conflict (latest save wins)\n";
  $q("UPDATE rfq SET partner_shared_updated_at = '2026-10-20 10:00:00' WHERE id = :i", [':i' => $mine]);
  $r1 = PartnerSyncService::applyIncoming($c, array_merge($rec, ['shared_updated_at' => '2026-10-19T10:00:00Z', 'name' => 'STALE']));
  check('older incoming ignored', 'ignored_stale', $r1['outcome']);
  check('older incoming changed nothing', 'Partner title v3', quoteRow($c, $mine)['name']);
  $r1b = PartnerSyncService::applyIncoming($c, array_merge($rec, ['shared_updated_at' => '2026-10-20T10:00:00Z', 'name' => 'TIE']));
  check('equal timestamp ignored (≤)', 'ignored_stale', $r1b['outcome']);
  $r2 = PartnerSyncService::applyIncoming($c, array_merge($rec, ['shared_updated_at' => '2026-10-21T10:00:00Z', 'name' => 'NEWER']));
  check('newer incoming applied', 'NEWER', quoteRow($c, $mine)['name']);
  check('our timestamp set to theirs, not now', '2026-10-21 10:00:00', quoteRow($c, $mine)['partner_shared_updated_at']);

  // ---------------------------------------------------------------- 8. ambiguous
  echo "-- 8. ambiguous\n";
  $dupA = mkQuote($c); $dupB = mkQuote($c);
  $q("UPDATE rfq SET solicitation_number = 'DUP', solicitation_key = :k WHERE id IN (:a, :b)", [':k' => "DUP-$run", ':a' => $dupA, ':b' => $dupB]);
  $rec = incoming(['solicitation_number' => "DUP-$run"]);
  $res = PartnerSyncService::applyIncoming($c, $rec);
  check('outcome review', 'review', $res['outcome']);
  check('neither duplicate linked', 0, (int)$val('SELECT COUNT(*) FROM rfq WHERE partner_record_id = :r', [':r' => $rec['record_id']]));
  $rv = $one("SELECT * FROM partner_review WHERE partner_record_id = :r AND resolved_at IS NULL", [':r' => $rec['record_id']]);
  check('listed as ambiguous', 'ambiguous', $rv['kind']);
  check('both candidates listed', [$dupA, $dupB], array_map('intval', json_decode($rv['candidates'], true)));
  PartnerSyncService::applyIncoming($c, $rec);
  check('redelivery does not pile up review rows', 1, (int)$val("SELECT COUNT(*) FROM partner_review WHERE partner_record_id = :r AND resolved_at IS NULL", [':r' => $rec['record_id']]));

  // ---------------------------------------------------------------- 8b. possible existing (Code)
  echo "-- 8b. possible existing quote by Code\n";
  $unkeyed = mkQuote($c, ['email_code' => "rfq-$run  0418", 'name' => 'Unkeyed in-flight']);
  $quotesBefore = (int)$val('SELECT COUNT(*) FROM rfq');
  $rec = incoming(['solicitation_number' => "RFQ-$run 0418", 'name' => 'Partner copy of in-flight']);
  $res = PartnerSyncService::applyIncoming($c, $rec);
  check('outcome review (not auto-created, not auto-linked)', 'review', $res['outcome']);
  check('no quote created', $quotesBefore, (int)$val('SELECT COUNT(*) FROM rfq'));
  check('unkeyed quote not linked', null, quoteRow($c, $unkeyed)['partner_record_id']);
  $rv = $one("SELECT * FROM partner_review WHERE partner_record_id = :r AND resolved_at IS NULL", [':r' => $rec['record_id']]);
  check('listed as possible', 'possible', $rv['kind']);
  check('candidate is the unkeyed quote', [$unkeyed], array_map('intval', json_decode($rv['candidates'], true)));
  $out = PartnerSyncService::resolveReview($c, (int)$rv['id'], 'link', $adminId, $unkeyed);
  check('Link resolves to the chosen quote', $unkeyed, $out['id_rfq']);
  $uq = quoteRow($c, $unkeyed);
  check('…which is now pinned + shared', [$rec['record_id'], 1], [$uq['partner_record_id'], (int)$uq['share_with_partner']]);
  check('…and keyed from the incoming number', "RFQ-$run 0418", $uq['solicitation_key']);
  check('review row closed', true, $val('SELECT resolved_at FROM partner_review WHERE id = :i', [':i' => $rv['id']]) !== null);

  $unkeyed2 = mkQuote($c, ['email_code' => "CODE-$run-B"]);
  $rec2 = incoming(['solicitation_number' => "CODE-$run-B"]);
  PartnerSyncService::applyIncoming($c, $rec2);
  $rv2 = $one("SELECT * FROM partner_review WHERE partner_record_id = :r AND resolved_at IS NULL", [':r' => $rec2['record_id']]);
  $quotesBefore = (int)$val('SELECT COUNT(*) FROM rfq');
  $out2 = PartnerSyncService::resolveReview($c, (int)$rv2['id'], 'create', $adminId);
  check('Create new makes exactly one more quote', $quotesBefore + 1, (int)$val('SELECT COUNT(*) FROM rfq'));
  check('…which is a different quote from the unkeyed one', true, $out2['id_rfq'] !== $unkeyed2);
  check('…and the unkeyed quote stays unlinked', null, quoteRow($c, $unkeyed2)['partner_record_id']);

  // ---------------------------------------------------------------- 13. removals
  echo "-- 13. removals\n";
  // our side: unshare → tombstone + removed notice + link detached
  $q('UPDATE partner_webhook_outbox SET delivered_at = :d WHERE id_rfq = :i', [':d' => gmdate('Y-m-d H:i:s'), ':i' => $mine]);
  PartnerShareRepository::saveShareSettings($c, $mine, false, "LINK-$run-FIXED");
  PartnerSyncService::reconcile($c, $mine);
  $mr = quoteRow($c, $mine);
  check('unshare sets tombstone', true, $mr['partner_removed_at'] !== null);
  check('unshare detaches the link', null, $mr['partner_record_id']);
  check('removed notice queued once', 1, outboxCount($c, $mine, 'opportunity.removed'));
  PartnerSyncService::reconcile($c, $mine);
  check('…not repeated', 1, outboxCount($c, $mine, 'opportunity.removed'));
  check('partner_unlink audit written', 1, (int)$val("SELECT COUNT(*) FROM audit_trails WHERE id_rfq = :i AND action_type = 'partner_unlink'", [':i' => $mine]));
  // ---- 4. tombstones in changed-since, indefinitely
  echo "-- 4. changed-since\n";
  $page = PartnerApi::handle($c, 'GET', ['partner', 'opportunities'], ['changed_since' => '2000-01-01T00:00:00Z'], $auth, '', $now + 86400 * 400)['json'];
  $tomb = null;
  foreach ($page['data'] as $d) { if (($d['elogic_id'] ?? null) === $mine) $tomb = $d; }
  check('unshared quote appears as tombstone', true, is_array($tomb) && $tomb['removed'] === true);
  // deleted shared quote → tombstone too
  $del = mkQuote($c, ['name' => 'To be deleted']);
  PartnerShareRepository::saveShareSettings($c, $del, true, "DEL-$run");
  PartnerSyncService::reconcile($c, $del);
  $q('UPDATE rfq SET deleted = 1 WHERE id = :i', [':i' => $del]);
  PartnerSyncService::reconcile($c, $del);
  check('deleting a shared quote queues removed', 1, outboxCount($c, $del, 'opportunity.removed'));
  check('deleted quote tombstoned', true, quoteRow($c, $del)['partner_removed_at'] !== null);
  // stable order + pagination
  $seen = []; $cursor = null; $pages = 0;
  do {
    $qs = ['changed_since' => '2000-01-01T00:00:00Z', 'limit' => '2'];
    if ($cursor) $qs['cursor'] = $cursor;
    $pg = PartnerApi::handle($c, 'GET', ['partner', 'opportunities'], $qs, $auth, '', $now)['json'];
    foreach ($pg['data'] as $d) $seen[] = $d['elogic_id'];
    $cursor = $pg['next_cursor'] ?? null; $pages++;
  } while ($cursor && $pages < 50);
  check('pagination visits every record exactly once', count($seen), count(array_unique($seen)));
  check('pagination actually paged', true, $pages > 1);
  check('tombstones included while paging', true, in_array($mine, $seen, true) && in_array($del, $seen, true));
  $full = PartnerApi::handle($c, 'GET', ['partner', 'opportunities'], ['changed_since' => '2000-01-01T00:00:00Z', 'limit' => '500'], $auth, '', $now)['json'];
  check('order is stable and matches the paged walk', $seen, array_column($full['data'], 'elogic_id'));
  $future = PartnerApi::handle($c, 'GET', ['partner', 'opportunities'], ['changed_since' => '2100-01-01T00:00:00Z'], $auth, '', $now)['json'];
  check('changed_since in the future → empty', [], $future['data']);

  // re-share clears the tombstone and queues an update
  $q('UPDATE partner_webhook_outbox SET delivered_at = :d WHERE id_rfq = :i', [':d' => gmdate('Y-m-d H:i:s'), ':i' => $mine]);
  PartnerShareRepository::saveShareSettings($c, $mine, true, "LINK-$run-FIXED");
  PartnerSyncService::reconcile($c, $mine);
  check('re-share clears tombstone', null, quoteRow($c, $mine)['partner_removed_at']);

  // incoming removed: untouched quote is hidden
  $recU = incoming(['solicitation_number' => "UNT-$run"]);
  $resU = PartnerSyncService::applyIncoming($c, $recU);
  $outBefore = outboxCount($c);
  $rmU = PartnerSyncService::applyRemoval($c, $recU['record_id'], $recU['solicitation_number']);
  $uq = quoteRow($c, $resU['id_rfq']);
  check('untouched quote hidden on partner removal', 1, (int)$uq['deleted']);
  check('link detached', null, $uq['partner_record_id']);
  check('sharing stopped', 0, (int)$uq['share_with_partner']);
  check('partner_removed audit written', 1, (int)$val("SELECT COUNT(*) FROM audit_trails WHERE id_rfq = :i AND action_type = 'partner_removed'", [':i' => $resU['id_rfq']]));
  check('applying removal enqueued nothing (no echo)', $outBefore, outboxCount($c));
  // incoming removed: priced / submitted / awarded quotes stay visible
  foreach ([['priced', ['completado' => 1, 'total_price' => 500]], ['submitted', ['status' => 1, 'completado' => 1]], ['awarded', ['award' => 1, 'status' => 1, 'completado' => 1]]] as [$label, $over]) {
    $pq = mkQuote($c, $over);
    PartnerShareRepository::saveShareSettings($c, $pq, true, "KEEP-$run-$label");
    $q('UPDATE rfq SET partner_record_id = :r, partner_linked_at = :t WHERE id = :i', [':r' => "PK-$label-$run", ':t' => gmdate('Y-m-d H:i:s'), ':i' => $pq]);
    PartnerSyncService::applyRemoval($c, "PK-$label-$run", "KEEP-$run-$label");
    $pr = quoteRow($c, $pq);
    check("$label quote stays visible", 0, (int)$pr['deleted']);
    check("$label quote is unlinked", null, $pr['partner_record_id']);
  }

  // ---------------------------------------------------------------- 14. statuses after award
  echo "-- 14. status limits\n";
  $aw = mkQuote($c, ['award' => 1, 'status' => 1, 'completado' => 1, 'fullfillment' => 1]);
  PartnerShareRepository::saveShareSettings($c, $aw, true, "AW-$run");
  PartnerSyncService::reconcile($c, $aw);
  $awPayload = PartnerApi::handle($c, 'GET', ['partner', 'opportunities', "AW-$run"], [], $auth, '', $now)['json'];
  check('fulfillment-stage quote is sent as award, nothing later', 'award', $awPayload['status']);
  $q("UPDATE rfq SET partner_record_id = 'P-AW', partner_shared_updated_at = '2026-01-01 00:00:00' WHERE id = :i", [':i' => $aw]);
  $ra = PartnerSyncService::applyIncoming($c, incoming(['record_id' => 'P-AW', 'solicitation_number' => "AW-$run", 'status' => 'cancelled', 'shared_updated_at' => '2026-10-01T00:00:00Z']));
  check('inbound status ignored once awarded', 'award', strtolower(( new Rfq(quoteRow($c, $aw)) )->getSheetStatus()));
  check('…comments untouched', 'No comments', quoteRow($c, $aw)['comments']);
  $pre = mkQuote($c);
  PartnerShareRepository::saveShareSettings($c, $pre, true, "PRE-$run");
  $q("UPDATE rfq SET partner_record_id = 'P-PRE' WHERE id = :i", [':i' => $pre]);
  PartnerSyncService::applyIncoming($c, incoming(['record_id' => 'P-PRE', 'solicitation_number' => "PRE-$run", 'status' => 'no_bid']));
  check('pre-award inbound no_bid is applied', 'No Bid', quoteRow($c, $pre)['comments']);

  // ---------------------------------------------------------------- 12. dispatcher + back-off
  echo "-- 12. delivery + retry\n";
  $disp = mkQuote($c, ['name' => 'Dispatch me']);
  PartnerShareRepository::saveShareSettings($c, $disp, true, "DISP-$run");
  $q('DELETE FROM partner_webhook_outbox WHERE delivered_at IS NULL AND failed_at IS NULL'); // isolate from earlier fixtures
  PartnerSyncService::reconcile($c, $disp);
  $calls = [];
  $mode = 'fail';
  PartnerSyncService::$http = function ($method, $url, $headers, $body, $timeout) use (&$calls, &$mode, $secret) {
    $calls[] = ['method' => $method, 'url' => $url, 'headers' => $headers, 'body' => $body];
    return $mode === 'ok' ? ['status' => 200, 'body' => '{}', 'error' => null] : ['status' => 503, 'body' => '', 'error' => null];
  };
  $t0 = time();
  $r = PartnerSyncService::dispatchDue($c, $t0);
  check('first attempt made', 1, count($calls));
  check('posted to the partner webhook url', 'https://hooks.partner.test/elogic', $calls[0]['url']);
  $h = array_change_key_case($calls[0]['headers'], CASE_LOWER);
  check('partner token sent as bearer', 'Bearer ptk_test', $h['authorization']);
  check('request is signed', hash_hmac('sha256', $h['x-timestamp'] . '.' . $calls[0]['body'], $secret), $h['x-signature']);
  $ob = $one('SELECT * FROM partner_webhook_outbox WHERE id_rfq = :i', [':i' => $disp]);
  check('attempt counted', 1, (int)$ob['attempts']);
  check('error recorded', true, strpos((string)$ob['last_error'], '503') !== false);
  check('next attempt in ~1 minute', 60, strtotime($ob['next_attempt_at'] . ' UTC') - $t0);
  $calls = []; PartnerSyncService::dispatchDue($c, $t0 + 30);
  check('not due yet → no call', 0, count($calls));
  $expected = [300, 1800, 7200, 43200];
  $tt = $t0 + 61;
  foreach ($expected as $i => $gap) {
    PartnerSyncService::dispatchDue($c, $tt);
    $ob = $one('SELECT * FROM partner_webhook_outbox WHERE id_rfq = :i', [':i' => $disp]);
    check('back-off step ' . ($i + 2), $gap, strtotime($ob['next_attempt_at'] . ' UTC') - $tt);
    $tt = strtotime($ob['next_attempt_at'] . ' UTC') + 1;
  }
  PartnerSyncService::dispatchDue($c, $tt); // 6th and final failure
  $ob = $one('SELECT * FROM partner_webhook_outbox WHERE id_rfq = :i', [':i' => $disp]);
  check('marked failed after the schedule is exhausted', true, $ob['failed_at'] !== null);
  check('six attempts total', 6, (int)$ob['attempts']);
  $failed = PartnerOutboxRepository::failed($c);
  check('shows in failed deliveries', true, in_array((int)$ob['id'], array_map('intval', array_column($failed, 'id')), true));
  $calls = []; PartnerSyncService::dispatchDue($c, $tt + 999999);
  check('failed rows are not retried automatically', 0, count($calls));
  PartnerOutboxRepository::retry($c, (int)$ob['id'], $tt + 1000);
  $mode = 'ok'; $calls = [];
  PartnerSyncService::dispatchDue($c, $tt + 1000);
  $ob = $one('SELECT * FROM partner_webhook_outbox WHERE id = :i', [':i' => $ob['id']]);
  check('Retry re-queues and delivers', true, $ob['delivered_at'] !== null && $ob['failed_at'] === null);
  check('last webhook success recorded', true, PartnerIntegrationRepository::get($c)['last_webhook_success_at'] !== null);
} catch (Throwable $e) {
  $fail++;
  echo "  FAIL  unexpected " . get_class($e) . ': ' . $e->getMessage() . "\n    at " . $e->getFile() . ':' . $e->getLine() . "\n";
} finally {
  $c->rollBack();
  Conexion::cerrar_conexion();
  if (isset($tmpDocs) && is_dir($tmpDocs)) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($tmpDocs, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($it as $f) { $f->isDir() ? rmdir($f->getPathname()) : unlink($f->getPathname()); }
    rmdir($tmpDocs);
  }
}

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
