<?php

/**
 * PartnerDocumentRepository
 *
 * Per-file share marks for a quote's folder (documentos/{id}/). Files are plain disk files with no
 * table of their own, so rows here appear lazily (shared = 0) the first time a folder is looked at.
 *
 * Two kinds of file can never be shared outward:
 *   • files the app generates (checklist / proposal / re-quote / tracking PDFs) — they carry
 *     internal pricing; enforced here, not just hidden in the UI;
 *   • files that originated at the partner (partner_file_id set) — they already have them.
 */
class PartnerDocumentRepository {

  /** Override in tests; defaults to the app's documentos/ folder. */
  public static $baseDir = null;

  public static function baseDir() {
    return self::$baseDir ?: dirname(__DIR__, 2) . '/documentos';
  }

  public static function folder($idRfq) {
    return self::baseDir() . '/' . (int)$idRfq;
  }

  /** Same name patterns the PDF scripts write: "<code>(proposal).pdf", "(checklist)", "(re_quote_items_table)", "(trackings)". */
  public static function isGenerated($fileName) {
    return (bool)preg_match('/\((proposal|checklist|re_quote_items_table|trackings)\)\.pdf$/i', $fileName);
  }

  /** A bare file name that cannot escape the quote's folder. */
  public static function safeName($fileName) {
    $name = basename(str_replace('\\', '/', (string)$fileName));
    return ($name === '' || $name === '.' || $name === '..') ? null : $name;
  }

  public static function diskFiles($idRfq) {
    $dir = self::folder($idRfq);
    if (!is_dir($dir)) return [];
    $out = [];
    foreach (scandir($dir) as $f) {
      if ($f !== '.' && $f !== '..' && is_file("$dir/$f")) $out[] = $f;
    }
    return $out;
  }

  /** Make sure a row exists for every file on disk (shared = 0). Cheap and idempotent. */
  public static function syncFolder($conexion, $idRfq) {
    $stmt = $conexion->prepare('INSERT IGNORE INTO partner_documents (id_rfq, file_name, shared) VALUES (:r, :f, 0)');
    foreach (self::diskFiles($idRfq) as $f) {
      $stmt->execute([':r' => $idRfq, ':f' => $f]);
    }
  }

  public static function find($conexion, $idRfq, $fileName) {
    $stmt = $conexion->prepare('SELECT * FROM partner_documents WHERE id_rfq = :r AND file_name = :f');
    $stmt->execute([':r' => $idRfq, ':f' => $fileName]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  /**
   * Share state for the Documents drawer, keyed by file name:
   * [name => ['shared' => bool, 'generated' => bool, 'from_partner' => bool]]
   */
  public static function stateByName($conexion, $idRfq) {
    $stmt = $conexion->prepare('SELECT file_name, shared, partner_file_id, removed_at FROM partner_documents WHERE id_rfq = :r');
    $stmt->execute([':r' => $idRfq]);
    $state = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $d) {
      $fromPartner = $d['partner_file_id'] !== null;
      $state[$d['file_name']] = [
        'shared' => $fromPartner ? true : ((int)$d['shared'] === 1 && $d['removed_at'] === null),
        'generated' => self::isGenerated($d['file_name']),
        'from_partner' => $fromPartner,
      ];
    }
    return $state;
  }

  /**
   * Mark a file shared / not shared. Returns false when it is not allowed (generated file,
   * partner-origin file, missing file, or the quote itself is not shared). A no-op change returns
   * true without queueing anything. A real change queues exactly one notice.
   */
  public static function setShared($conexion, $idRfq, $fileName, $shared) {
    $name = self::safeName($fileName);
    if ($name === null || self::isGenerated($name) || !is_file(self::folder($idRfq) . '/' . $name)) return false;
    $quote = PartnerShareRepository::row($conexion, $idRfq);
    if (!$quote || (int)$quote['deleted'] === 1 || (int)$quote['share_with_partner'] !== 1) return false;

    self::syncFolder($conexion, $idRfq);
    $doc = self::find($conexion, $idRfq, $name);
    if (!$doc || $doc['partner_file_id'] !== null) return false;
    $wasShared = (int)$doc['shared'] === 1 && $doc['removed_at'] === null;
    if ($wasShared === (bool)$shared) return true;

    $now = gmdate('Y-m-d H:i:s');
    $path = self::folder($idRfq) . '/' . $name;
    $conexion->prepare('UPDATE partner_documents SET shared = :s, size_bytes = :z, sha256 = :h, shared_updated_at = :t, removed_at = NULL
        WHERE id = :id')
      ->execute([':s' => $shared ? 1 : 0, ':z' => filesize($path), ':h' => hash_file('sha256', $path), ':t' => $now, ':id' => $doc['id']]);
    PartnerOutboxRepository::enqueue($conexion, $idRfq, $shared ? 'document.shared' : 'document.removed',
      PartnerSyncService::noticePayload($quote, $shared ? 'document.shared' : 'document.removed'));
    return true;
  }

  /** Shared, outward-facing files of one quote with the facts the partner needs to fetch them. */
  public static function sharedFiles($conexion, $idRfq) {
    $stmt = $conexion->prepare('SELECT * FROM partner_documents WHERE id_rfq = :r AND shared = 1
        AND partner_file_id IS NULL AND removed_at IS NULL ORDER BY file_name');
    $stmt->execute([':r' => $idRfq]);
    $out = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $d) {
      $path = self::folder($idRfq) . '/' . $d['file_name'];
      if (!is_file($path) || self::isGenerated($d['file_name'])) continue;
      $out[] = [
        'id' => (int)$d['id'],
        'name' => $d['file_name'],
        'size' => (int)filesize($path),
        'sha256' => hash_file('sha256', $path),
        'updated_at' => self::iso($d['shared_updated_at']),
        'download_url' => SERVIDOR . 'api/partner/files/' . (int)$d['id'],
      ];
    }
    return $out;
  }

  /**
   * One downloadable file by id, or null. The same gate as the listing: shared, not generated,
   * not partner-origin, not removed, on a shared non-deleted quote, and present on disk.
   */
  public static function downloadable($conexion, $fileId) {
    $stmt = $conexion->prepare('SELECT d.*, r.share_with_partner, r.deleted FROM partner_documents d
        JOIN rfq r ON r.id = d.id_rfq WHERE d.id = :id');
    $stmt->execute([':id' => $fileId]);
    $d = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$d || (int)$d['shared'] !== 1 || $d['partner_file_id'] !== null || $d['removed_at'] !== null
        || (int)$d['share_with_partner'] !== 1 || (int)$d['deleted'] === 1 || self::isGenerated($d['file_name'])) {
      return null;
    }
    $path = self::folder($d['id_rfq']) . '/' . $d['file_name'];
    return is_file($path) ? ['path' => $path, 'name' => $d['file_name'], 'size' => (int)filesize($path)] : null;
  }

  /**
   * Notice-worthy file changes for a quote: a shared file whose content changed on disk (re-upload
   * under the same name) or that disappeared. Updates the rows and queues one notice per kind.
   * Returns the number of changes found.
   */
  public static function reconcileShared($conexion, $idRfq) {
    $quote = PartnerShareRepository::row($conexion, $idRfq);
    if (!$quote || (int)$quote['share_with_partner'] !== 1 || (int)$quote['deleted'] === 1) return 0;
    $stmt = $conexion->prepare('SELECT * FROM partner_documents WHERE id_rfq = :r AND shared = 1
        AND partner_file_id IS NULL AND removed_at IS NULL');
    $stmt->execute([':r' => $idRfq]);
    $changes = 0;
    $now = gmdate('Y-m-d H:i:s');
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $d) {
      $path = self::folder($idRfq) . '/' . $d['file_name'];
      if (!is_file($path)) {
        $conexion->prepare('UPDATE partner_documents SET removed_at = :t, shared_updated_at = :t WHERE id = :id')->execute([':t' => $now, ':id' => $d['id']]);
        PartnerOutboxRepository::enqueue($conexion, $idRfq, 'document.removed', PartnerSyncService::noticePayload($quote, 'document.removed'));
        $changes++;
      } elseif (hash_file('sha256', $path) !== $d['sha256']) {
        $conexion->prepare('UPDATE partner_documents SET sha256 = :h, size_bytes = :z, shared_updated_at = :t WHERE id = :id')
          ->execute([':h' => hash_file('sha256', $path), ':z' => filesize($path), ':t' => $now, ':id' => $d['id']]);
        PartnerOutboxRepository::enqueue($conexion, $idRfq, 'document.shared', PartnerSyncService::noticePayload($quote, 'document.shared'));
        $changes++;
      }
    }
    return $changes;
  }

  public static function iso($utcDatetime) {
    return $utcDatetime ? gmdate('Y-m-d\TH:i:s\Z', strtotime($utcDatetime . ' UTC')) : null;
  }
}
