<?php

/**
 * QuoteWatcherRepository
 *
 * CRUD for per-quote "watcher" subscriptions (quote_watchers). Watching is idempotent
 * (unique on id_rfq + id_user) and carries no audit trail — it's a personal notification
 * preference, not a quote-content change.
 */
class QuoteWatcherRepository {

  /** Subscribe a user to a quote. No-op if already watching. */
  public static function watch($conexion, $id_rfq, $id_user) {
    if (!isset($conexion) || !$id_rfq || !$id_user) return;
    try {
      $stmt = $conexion->prepare('INSERT IGNORE INTO quote_watchers (id_rfq, id_user) VALUES (:r, :u)');
      $stmt->bindValue(':r', $id_rfq, PDO::PARAM_INT);
      $stmt->bindValue(':u', $id_user, PDO::PARAM_INT);
      $stmt->execute();
    } catch (PDOException $ex) {
      error_log('QuoteWatcherRepository::watch error: ' . $ex->getMessage());
    }
  }

  /** Unsubscribe a user from a quote. */
  public static function unwatch($conexion, $id_rfq, $id_user) {
    if (!isset($conexion) || !$id_rfq || !$id_user) return;
    try {
      $stmt = $conexion->prepare('DELETE FROM quote_watchers WHERE id_rfq = :r AND id_user = :u');
      $stmt->bindValue(':r', $id_rfq, PDO::PARAM_INT);
      $stmt->bindValue(':u', $id_user, PDO::PARAM_INT);
      $stmt->execute();
    } catch (PDOException $ex) {
      error_log('QuoteWatcherRepository::unwatch error: ' . $ex->getMessage());
    }
  }

  public static function isWatching($conexion, $id_rfq, $id_user) {
    if (!isset($conexion) || !$id_rfq || !$id_user) return false;
    try {
      $stmt = $conexion->prepare('SELECT 1 FROM quote_watchers WHERE id_rfq = :r AND id_user = :u LIMIT 1');
      $stmt->bindValue(':r', $id_rfq, PDO::PARAM_INT);
      $stmt->bindValue(':u', $id_user, PDO::PARAM_INT);
      $stmt->execute();
      return (bool)$stmt->fetchColumn();
    } catch (PDOException $ex) {
      return false;
    }
  }

  /** All user ids watching a quote (drives notification fan-out). */
  public static function getWatcherIds($conexion, $id_rfq) {
    if (!isset($conexion) || !$id_rfq) return [];
    try {
      $stmt = $conexion->prepare('SELECT id_user FROM quote_watchers WHERE id_rfq = :r');
      $stmt->bindValue(':r', $id_rfq, PDO::PARAM_INT);
      $stmt->execute();
      return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    } catch (PDOException $ex) {
      error_log('QuoteWatcherRepository::getWatcherIds error: ' . $ex->getMessage());
      return [];
    }
  }
}
