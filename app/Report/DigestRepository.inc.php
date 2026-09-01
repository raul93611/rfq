<?php

/**
 * DigestRepository
 *
 * Backing queries for the Daily RFQ Digest cron (scripts/cron/daily_digest.php): the four
 * "what happened" lists (Created/Submitted/Awarded on a given day, Due (by End Date) on a
 * given day) plus the digest_send_log dedup guard. All four list queries share the same row
 * shape so the email template can render them identically.
 */
class DigestRepository {

  private static function selectByDate($conexion, $date_column, $date) {
    $rows = [];
    if (!isset($conexion)) return $rows;
    try {
      $sql = "SELECT rfq.id, rfq.name, rfq.client, rfq.canal, usuarios.nombres, usuarios.apellidos
              FROM rfq
              LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id
              WHERE rfq.deleted = 0 AND DATE(rfq.{$date_column}) = :date
              ORDER BY rfq.id";
      $stmt = $conexion->prepare($sql);
      $stmt->bindValue(':date', $date, PDO::PARAM_STR);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
      error_log('DigestRepository::selectByDate error: ' . $ex->getMessage());
    }
    return $rows;
  }

  /** Quotes created on the given calendar date. */
  public static function getCreatedOn($conexion, $date) {
    return self::selectByDate($conexion, 'created_at', $date);
  }

  /** Quotes submitted on the given calendar date. */
  public static function getSubmittedOn($conexion, $date) {
    return self::selectByDate($conexion, 'fecha_submitted', $date);
  }

  /** Quotes awarded on the given calendar date. */
  public static function getAwardedOn($conexion, $date) {
    return self::selectByDate($conexion, 'fecha_award', $date);
  }

  /**
   * Quotes whose End Date is the given calendar date. rfq.end_date is a VARCHAR
   * ("MM/DD/YYYY HH:mm"), not a DATE column, and is NOT NULL with no default, so an unset
   * value is '' rather than SQL NULL -- NULLIF blanks that out first, mirroring the same
   * handling in PipelineTableRepository::buildWhere()'s End Date range filter.
   */
  public static function getDueOn($conexion, $date) {
    $rows = [];
    if (!isset($conexion)) return $rows;
    try {
      $sql = "SELECT rfq.id, rfq.name, rfq.client, rfq.canal, usuarios.nombres, usuarios.apellidos
              FROM rfq
              LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id
              WHERE rfq.deleted = 0 AND DATE(STR_TO_DATE(NULLIF(rfq.end_date, ''), '%m/%d/%Y %H:%i')) = :date
              ORDER BY rfq.id";
      $stmt = $conexion->prepare($sql);
      $stmt->bindValue(':date', $date, PDO::PARAM_STR);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
      error_log('DigestRepository::getDueOn error: ' . $ex->getMessage());
    }
    return $rows;
  }

  /** True when a digest run already completed for the given calendar date. */
  public static function hasSentOn($conexion, $date) {
    if (!isset($conexion)) return false;
    try {
      $stmt = $conexion->prepare('SELECT 1 FROM digest_send_log WHERE digest_date = :date');
      $stmt->bindValue(':date', $date, PDO::PARAM_STR);
      $stmt->execute();
      return (bool) $stmt->fetchColumn();
    } catch (PDOException $ex) {
      error_log('DigestRepository::hasSentOn error: ' . $ex->getMessage());
      return false;
    }
  }

  /** Marks the given calendar date as having completed a digest run. */
  public static function markSent($conexion, $date) {
    if (!isset($conexion)) return;
    try {
      $stmt = $conexion->prepare('INSERT IGNORE INTO digest_send_log (digest_date) VALUES (:date)');
      $stmt->bindValue(':date', $date, PDO::PARAM_STR);
      $stmt->execute();
    } catch (PDOException $ex) {
      error_log('DigestRepository::markSent error: ' . $ex->getMessage());
    }
  }
}
