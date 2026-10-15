<?php

/**
 * PartnerShareRepository
 *
 * Per-quote sharing state on `rfq`: the Share-with-partner gate, the Solicitation # and its
 * normalized matching key, plus the lookups the matcher needs. Sync *decisions* (what to enqueue,
 * what to apply) live in PartnerSyncService — this class only reads and writes columns.
 */
class PartnerShareRepository {

  const ERR_REQUIRED = 'Solicitation # is required to share with the partner.';

  /** trim + uppercase + collapse whitespace; null when nothing is left. */
  public static function normalizeKey($value) {
    $v = preg_replace('/\s+/u', ' ', trim(mb_strtoupper((string)$value, 'UTF-8')));
    return $v === '' ? null : $v;
  }

  /**
   * Validation shared by New Quote and the Information drawer. Returns an error message or null.
   * The number is required only while sharing is on; uniqueness of the normalized key is checked
   * among non-deleted quotes whenever a number is given (so the key stays a reliable match).
   */
  public static function validateSolicitation($conexion, $share, $solicitation, $excludeIdRfq = null) {
    $number = trim((string)$solicitation);
    if ($share && $number === '') return self::ERR_REQUIRED;
    if (mb_strlen($number, 'UTF-8') > 255) return 'Solicitation # must be 255 characters or fewer.';
    $key = self::normalizeKey($number);
    if ($key === null) return null;
    $others = array_filter(self::findByKey($conexion, $key), function ($r) use ($excludeIdRfq) {
      return $excludeIdRfq === null || (int)$r['id'] !== (int)$excludeIdRfq;
    });
    if ($others) return 'Another quote (#' . reset($others)['id'] . ') already uses this Solicitation #.';
    return null;
  }

  /** Persist the gate + number + key. Callers run PartnerSyncService::reconcile() afterwards. */
  public static function saveShareSettings($conexion, $idRfq, $share, $solicitation) {
    $number = trim((string)$solicitation);
    $conexion->prepare('UPDATE rfq SET share_with_partner = :s, solicitation_number = :n, solicitation_key = :k WHERE id = :id')
      ->execute([
        ':s' => $share ? 1 : 0,
        ':n' => $number === '' ? null : $number,
        ':k' => self::normalizeKey($number),
        ':id' => $idRfq,
      ]);
  }

  public static function row($conexion, $idRfq) {
    $stmt = $conexion->prepare('SELECT * FROM rfq WHERE id = :id');
    $stmt->execute([':id' => $idRfq]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  /** Non-deleted quotes carrying this normalized key. */
  public static function findByKey($conexion, $key) {
    $stmt = $conexion->prepare('SELECT id, partner_record_id, share_with_partner FROM rfq WHERE solicitation_key = :k AND deleted = 0 ORDER BY id');
    $stmt->execute([':k' => $key]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /** The quote pinned to a partner record id. Deleted rows are included so a late notice is not re-created. */
  public static function findByRecordId($conexion, $recordId) {
    $stmt = $conexion->prepare('SELECT * FROM rfq WHERE partner_record_id = :r ORDER BY id LIMIT 1');
    $stmt->execute([':r' => $recordId]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  /**
   * Non-deleted quotes with no Solicitation # whose Code equals the key once normalized — the
   * launch-time safety net for in-flight opportunities that predate the field. SQL narrows on the
   * space-stripped value; PHP then applies the exact normalization so the two cannot disagree.
   */
  public static function findUnkeyedByCode($conexion, $key) {
    $stmt = $conexion->prepare("SELECT id, email_code FROM rfq WHERE deleted = 0 AND solicitation_key IS NULL
        AND email_code <> '' AND REPLACE(UPPER(TRIM(email_code)), ' ', '') = :compact ORDER BY id");
    $stmt->execute([':compact' => str_replace(' ', '', $key)]);
    return array_values(array_filter($stmt->fetchAll(PDO::FETCH_ASSOC), function ($r) use ($key) {
      return self::normalizeKey($r['email_code']) === $key;
    }));
  }
}
