<?php

/**
 * Daily RFQ Digest Email
 *
 * Sends one HTML email to every active Admin-role user summarizing quote activity:
 * quotes created/submitted/awarded on the previous calendar day, and quotes whose Internal
 * Due Date is today — always sent, even when every section is empty (doubles as a heartbeat
 * that the job is still running).
 *
 * Day-boundary math runs in America/New_York regardless of the underlying server/container
 * timezone, so this must NOT rely on the OS default (droplets commonly default to UTC).
 *
 * A digest_send_log row per calendar date is the dedup guard: a second trigger the same day
 * (whether or not the shared mailbox is connected) no-ops instead of double-sending.
 *
 * Crontab (production droplet, 6:00am America/New_York):
 *   0 6 * * * docker exec <php-container> php /var/www/html/rfq/scripts/cron/daily_digest.php
 *
 * Manual run: docker exec lamp-php84 php /var/www/html/rfq/scripts/cron/daily_digest.php
 */

date_default_timezone_set('America/New_York');

$root = __DIR__ . '/../../';
require_once $root . 'app/Bootstrap/config.inc.php';
require_once $root . 'app/Bootstrap/routes.inc.php';
require_once $root . 'app/Bootstrap/Conexion.inc.php';
require_once $root . 'app/Report/DigestRepository.inc.php';
require_once $root . 'app/User/Usuario.inc.php';
require_once $root . 'app/User/RepositorioUsuario.inc.php';
require_once $root . 'app/Setting/NotificationMailboxRepository.inc.php';
require_once $root . 'app/Utilities/NotificationEmail.inc.php';
require_once $root . 'app/Utilities/DigestEmailTemplate.inc.php';

$conexion = Conexion::obtener_conexion();

$today = date('Y-m-d');

if (DigestRepository::hasSentOn($conexion, $today)) {
  echo "Digest already sent for {$today}, skipping.\n";
  exit(0);
}

$yesterday = date('Y-m-d', strtotime('-1 day'));

$created   = DigestRepository::getCreatedOn($conexion, $yesterday);
$submitted = DigestRepository::getSubmittedOn($conexion, $yesterday);
$awarded   = DigestRepository::getAwardedOn($conexion, $yesterday);
$due       = DigestRepository::getDueOn($conexion, $today);

$date_label = date('l, F j, Y');
$html    = DigestEmailTemplate::render($date_label, $created, $submitted, $awarded, $due);
$subject = 'E-logic: Daily RFQ Digest — ' . $date_label;

$admins = RepositorioUsuario::getActiveAdminUsers($conexion);
$sent_count = 0;
foreach ($admins as $admin) {
  if (!$admin->is_admin() || !$admin->obtener_email()) continue;
  NotificationEmail::sendCustom($conexion, $admin->obtener_email(), $subject, $html);
  $sent_count++;
}

DigestRepository::markSent($conexion, $today);

echo "Daily digest for {$date_label}: "
  . count($created) . " created, " . count($submitted) . " submitted, "
  . count($awarded) . " awarded, " . count($due) . " due today. "
  . "Attempted send to {$sent_count} admin(s).\n";
