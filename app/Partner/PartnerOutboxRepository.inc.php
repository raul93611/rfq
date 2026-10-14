<?php

/**
 * PartnerOutboxRepository
 *
 * Light "X changed" notices waiting to be POSTed to the partner's webhook. The dispatcher cron
 * drains this table. Delivery is at-least-once with a bounded back-off, then the row is parked as
 * failed (admin list + manual Retry); the changed-since pull is the catch-up path after that.
 */
class PartnerOutboxRepository {

  /** Wait before attempt 2, 3, 4, 5, 6 — a failure after the sixth attempt parks the row. */
  const BACKOFF = [60, 300, 1800, 7200, 43200];

  private static function utc($ts) { return gmdate('Y-m-d H:i:s', $ts); }

  /**
   * Queue one notice. A notice of the same event for the same quote that is still waiting to be
   * sent absorbs this one (the fetch the partner makes is authoritative), so a burst of edits is
   * one webhook, not many. Returns false when coalesced.
   */
  public static function enqueue($conexion, $idRfq, $event, array $payload, $nowTs = null) {
    $stmt = $conexion->prepare('SELECT COUNT(*) FROM partner_webhook_outbox
        WHERE id_rfq = :r AND event = :e AND delivered_at IS NULL AND failed_at IS NULL');
    $stmt->execute([':r' => $idRfq, ':e' => $event]);
    if ((int)$stmt->fetchColumn() > 0) return false;
    $now = $nowTs ?: time();
    $conexion->prepare('INSERT INTO partner_webhook_outbox (id_rfq, event, payload, attempts, next_attempt_at, created_at)
        VALUES (:r, :e, :p, 0, :n, :c)')
      ->execute([':r' => $idRfq, ':e' => $event, ':p' => json_encode($payload), ':n' => self::utc($now), ':c' => self::utc($now)]);
    return true;
  }

  public static function due($conexion, $nowTs, $limit = 50) {
    $stmt = $conexion->prepare('SELECT * FROM partner_webhook_outbox
        WHERE delivered_at IS NULL AND failed_at IS NULL AND next_attempt_at <= :n ORDER BY id LIMIT ' . (int)$limit);
    $stmt->execute([':n' => self::utc($nowTs)]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function find($conexion, $id) {
    $stmt = $conexion->prepare('SELECT * FROM partner_webhook_outbox WHERE id = :id');
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  /** Record one delivery attempt. Returns 'delivered' | 'retry' | 'failed'. */
  public static function recordResult($conexion, array $row, $ok, $error, $nowTs) {
    $attempts = (int)$row['attempts'] + 1;
    if ($ok) {
      $conexion->prepare('UPDATE partner_webhook_outbox SET attempts = :a, delivered_at = :d, last_error = NULL WHERE id = :id')
        ->execute([':a' => $attempts, ':d' => self::utc($nowTs), ':id' => $row['id']]);
      return 'delivered';
    }
    $error = mb_substr((string)$error, 0, 255);
    if ($attempts > count(self::BACKOFF)) {
      $conexion->prepare('UPDATE partner_webhook_outbox SET attempts = :a, failed_at = :f, last_error = :e WHERE id = :id')
        ->execute([':a' => $attempts, ':f' => self::utc($nowTs), ':e' => $error, ':id' => $row['id']]);
      return 'failed';
    }
    $conexion->prepare('UPDATE partner_webhook_outbox SET attempts = :a, next_attempt_at = :n, last_error = :e WHERE id = :id')
      ->execute([':a' => $attempts, ':n' => self::utc($nowTs + self::BACKOFF[$attempts - 1]), ':e' => $error, ':id' => $row['id']]);
    return 'retry';
  }

  /** Parked rows, newest first, with the quote name for the admin list. */
  public static function failed($conexion, $limit = 100) {
    $stmt = $conexion->prepare('SELECT o.*, r.name AS quote_name FROM partner_webhook_outbox o
        LEFT JOIN rfq r ON r.id = o.id_rfq
        WHERE o.failed_at IS NOT NULL AND o.delivered_at IS NULL ORDER BY o.failed_at DESC, o.id DESC LIMIT ' . (int)$limit);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /** Re-queue a parked row for an immediate attempt. A further failure parks it again at once. */
  public static function retry($conexion, $id, $nowTs = null) {
    $conexion->prepare('UPDATE partner_webhook_outbox SET failed_at = NULL, next_attempt_at = :n WHERE id = :id AND failed_at IS NOT NULL')
      ->execute([':n' => self::utc($nowTs ?: time()), ':id' => $id]);
  }
}
