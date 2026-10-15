<?php

/**
 * Partner Opportunity Sync — outbound dispatcher.
 *
 * Every minute: (1) reconcile every shared / previously shared quote so changes made through ANY
 * code path (status moves, deletes, edits) are noticed even if no save hook ran, then (2) POST the
 * due webhook notices to the partner with the 1m / 5m / 30m / 2h / 12h back-off.
 *
 * Crontab (production droplet, runs PHP directly — no Docker there):
 *   * * * * * php /var/www/elogicportal/rfq/scripts/cron/partner_webhook_dispatch.php
 *
 * Manual run, local dev: docker exec lamp-php83 php /var/www/html/rfq/scripts/cron/partner_webhook_dispatch.php
 */

if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/routes.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
foreach (['Rfq', 'AuditTrail', 'AuditTrailRepository'] as $cls) require_once $root . "app/Quote/$cls.inc.php";
foreach (['PartnerIntegrationRepository', 'PartnerShareRepository', 'PartnerDocumentRepository',
          'PartnerOutboxRepository', 'PartnerSyncService'] as $cls) require_once $root . "app/Partner/$cls.inc.php";

// One run at a time: a slow partner must not let the next minute's run double-send.
try {
  $lock = PartnerSyncService::acquireLock('partner_webhook_dispatch');
} catch (RuntimeException $e) {
  fwrite(STDERR, $e->getMessage() . "\n");
  exit(1);
}
if (!$lock) { echo "Previous dispatch still running, skipping.\n"; exit(0); }

$conexion = Conexion::obtener_conexion();
if (!PartnerIntegrationRepository::isEnabled($conexion)) { echo "Partner Integration disabled, skipping.\n"; exit(0); }

$scanned = PartnerSyncService::scanAll($conexion);
$r = PartnerSyncService::dispatchDue($conexion);
echo "Partner dispatch: scanned {$scanned} quote(s); "
  . ($r['skipped'] ? 'not configured (webhook URL / signing secret missing).' : "{$r['sent']} delivered, {$r['retry']} to retry, {$r['failed']} failed.") . "\n";
