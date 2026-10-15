<?php

/**
 * Partner Opportunity Sync — catch-up pull.
 *
 * Every 15 minutes: ask the partner for everything changed since the last pull (removals included)
 * and apply it. This is the backstop for missed webhooks; applying a record that was already
 * applied is a no-op under the latest-save-wins rule.
 *
 * Crontab (production droplet):
 *   0,15,30,45 * * * * php /var/www/elogicportal/rfq/scripts/cron/partner_pull.php
 *
 * Manual run, local dev: docker exec lamp-php83 php /var/www/html/rfq/scripts/cron/partner_pull.php
 */

if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/routes.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
foreach (['Rfq', 'RepositorioRfq', 'AuditTrail', 'AuditTrailRepository'] as $cls) require_once $root . "app/Quote/$cls.inc.php";
require_once $root . 'app/Utilities/Input.inc.php';
foreach (['PartnerIntegrationRepository', 'PartnerShareRepository', 'PartnerDocumentRepository',
          'PartnerOutboxRepository', 'PartnerSyncService'] as $cls) require_once $root . "app/Partner/$cls.inc.php";

try {
  $lock = PartnerSyncService::acquireLock('partner_pull');
} catch (RuntimeException $e) {
  fwrite(STDERR, $e->getMessage() . "\n");
  exit(1);
}
if (!$lock) { echo "Previous pull still running, skipping.\n"; exit(0); }

$conexion = Conexion::obtener_conexion();
$r = PartnerSyncService::pull($conexion);
if ($r['skipped']) { echo "Partner Integration disabled or base URL not set, skipping.\n"; exit(0); }
echo "Partner pull: {$r['seen']} record(s) seen, {$r['applied']} applied" . (isset($r['error']) ? ", stopped on error: {$r['error']}" : '') . ".\n";
exit(isset($r['error']) ? 1 : 0);
