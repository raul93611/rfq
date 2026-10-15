<?php

/**
 * PartnerSyncService
 *
 * The decision layer of Partner Opportunity Sync. Everything takes the PDO connection explicitly
 * and never reads $_SESSION unless an actor is passed — most callers are a webhook or a cron.
 *
 *   outbound  reconcile()      one idempotent call after ANY change: detects real shared-field
 *                              changes (payload hash), tombstones, restores and file changes, and
 *                              queues at most one notice per kind. Called from save paths for
 *                              immediacy and from the dispatcher cron as the safety net.
 *   inbound   applyIncoming()  match → link/create/review, latest-save-wins conflict rule, shared
 *                              fields only, never queues a notice back (no echo).
 *             applyRemoval()   detach; hide only an untouched quote.
 *
 * The partner's field list / payload is not final (see features/partner-opportunity-sync.md, Open
 * items). All field mapping lives in buildPayload() / normalizeIncoming() / applyFields() so the
 * agreed payload is a change in this one file.
 */
class PartnerSyncService {

  /** Test seam: callable($method, $url, array $headers, $body, $timeout) → ['status','body','error']. */
  public static $http = null;
  /** Test seam: overrides PARTNER_DEFAULT_ASSIGNEE_EMAIL. */
  public static $defaultAssigneeEmail = null;

  const SERVICE_USERNAME = 'partner_integration';
  const ACTOR_NAME = 'Partner Integration';
  const SIGNATURE_WINDOW = 300;
  const TZ = 'America/New_York';
  const MAX_DOWNLOAD_BYTES = 52428800; // 50 MB

  /** Inbound partner status → rfq.comments. Only pre-award "special" statuses; the rest of the
   *  pipeline is driven by our own workflow (completed/submitted/award have prerequisites). */
  const INBOUND_STATUS = ['no_bid' => 'No Bid', 'cancelled' => 'Cancelled', 'not_submitted' => 'Not submitted'];

  const STATUS_SLUGS = [
    'TBD' => 'tbd', 'WORKING ON IT' => 'working_on_it', 'BID' => 'bid', 'NO BID' => 'no_bid',
    'SUBMITTED' => 'submitted', 'SUBMITTED-SOURCES SOUGHT' => 'submitted_ss', 'AWARD' => 'award',
    'NO AWARD-PRICING' => 'no_award_pricing', 'NO AWARD-TECHNICAL' => 'no_award_technical',
    'CANCELLED' => 'cancelled', 'NOT SUBMITTED' => 'not_submitted',
  ];

  /* ------------------------------------------------------------------ small helpers */

  private static function utc($ts = null) { return gmdate('Y-m-d H:i:s', $ts ?: time()); }

  /** ISO-8601 / date string → 'Y-m-d H:i:s' UTC, or null. */
  public static function isoToUtc($iso) {
    if (!is_string($iso) || trim($iso) === '') return null;
    try {
      $d = new DateTime($iso, new DateTimeZone('UTC'));
      $d->setTimezone(new DateTimeZone('UTC'));
      return $d->format('Y-m-d H:i:s');
    } catch (Exception $e) { return null; }
  }

  /** Our 'm/d/Y' or 'm/d/Y H:i' (America/New_York) → 'Y-m-d' or ISO-8601 UTC; null when blank/unparseable. */
  public static function localToIso($value) {
    $value = trim((string)$value);
    if ($value === '') return null;
    $tz = new DateTimeZone(self::TZ);
    $withTime = DateTime::createFromFormat('!m/d/Y H:i', $value, $tz);
    if ($withTime) return $withTime->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d\TH:i:s\Z');
    $dateOnly = DateTime::createFromFormat('!m/d/Y', $value, $tz);
    return $dateOnly ? $dateOnly->format('Y-m-d') : null;
  }

  /** Inverse of localToIso(): date-only values stay date-only, timestamps go to America/New_York. */
  public static function isoToLocal($iso, $withTime) {
    if (!is_string($iso) || trim($iso) === '') return null;
    try {
      if (preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($iso))) {
        return (new DateTime(trim($iso)))->format($withTime ? 'm/d/Y 00:00' : 'm/d/Y');
      }
      $d = new DateTime($iso, new DateTimeZone('UTC'));
      return $d->setTimezone(new DateTimeZone(self::TZ))->format($withTime ? 'm/d/Y H:i' : 'm/d/Y');
    } catch (Exception $e) { return null; }
  }

  private static function serviceUser($conexion) {
    $stmt = $conexion->prepare('SELECT id FROM usuarios WHERE nombre_usuario = :u');
    $stmt->execute([':u' => self::SERVICE_USERNAME]);
    $id = $stmt->fetchColumn();
    return $id === false ? null : (int)$id;
  }

  private static function userIdByEmail($conexion, $email) {
    $email = trim((string)$email);
    if ($email === '') return null;
    $stmt = $conexion->prepare('SELECT id FROM usuarios WHERE LOWER(email) = LOWER(:e) AND status = 1 LIMIT 1');
    $stmt->execute([':e' => $email]);
    $id = $stmt->fetchColumn();
    return $id === false ? null : (int)$id;
  }

  /** ['username','id'] for the audit trail: the session user when there is one, else the service user. */
  private static function actor($conexion) {
    if (!empty($_SESSION['user']) && is_object($_SESSION['user'])) {
      return ['username' => $_SESSION['user']->obtener_nombre_usuario(), 'id' => $_SESSION['user']->obtener_id()];
    }
    return ['username' => self::ACTOR_NAME, 'id' => self::serviceUser($conexion)];
  }

  private static function partnerActor($conexion) {
    return ['username' => self::ACTOR_NAME, 'id' => self::serviceUser($conexion)];
  }

  private static function audit($conexion, $type, $message, $idRfq, array $actor) {
    AuditTrailRepository::partner_event($conexion, $type, $message, $idRfq, $actor['username'], $actor['id']);
  }

  private static function esc($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

  /* ------------------------------------------------------------------ payload */

  /** The light notice body queued in the outbox: what changed, never the data itself. */
  public static function noticePayload(array $row, $event) {
    return [
      'event' => $event,
      'elogic_id' => (int)$row['id'],
      'solicitation_number' => $row['solicitation_number'],
      'record_id' => $row['partner_record_id'],
      'occurred_at' => gmdate('Y-m-d\TH:i:s\Z'),
    ];
  }

  /**
   * The shared fields of a quote. Only these (plus the file list) ever leave the app; the hash of
   * this array is what detects a real change and suppresses echoes. Status is capped at Award —
   * Fulfillment and Invoice are internal and are reported as plain 'award'.
   */
  public static function buildPayload($conexion, array $row) {
    $slug = self::STATUS_SLUGS[(new Rfq($row))->getSheetStatus()] ?? 'tbd';
    $stmt = $conexion->prepare('SELECT email FROM usuarios WHERE id = :id');
    $stmt->execute([':id' => $row['usuario_designado']]);
    $email = $stmt->fetchColumn();
    return [
      'solicitation_number' => $row['solicitation_number'],
      'elogic_id' => (int)$row['id'],
      'name' => $row['name'],
      'agency' => $row['client'],
      'issue_date' => self::localToIso($row['issue_date']),
      'end_date' => self::localToIso($row['end_date']),
      'status' => $slug,
      'assignee_email' => $email === false ? null : $email,
    ];
  }

  public static function payloadHash(array $payload) { return hash('sha256', json_encode($payload)); }

  /** The full outward-facing opportunity (payload + timestamps + files), or a tombstone. */
  public static function opportunityJson($conexion, array $row) {
    if ($row['partner_removed_at'] !== null && ((int)$row['share_with_partner'] !== 1 || (int)$row['deleted'] === 1)) {
      return [
        'elogic_id' => (int)$row['id'], 'solicitation_number' => $row['solicitation_number'],
        'removed' => true, 'shared_updated_at' => PartnerDocumentRepository::iso($row['partner_removed_at']), 'files' => [],
      ];
    }
    return array_merge(self::buildPayload($conexion, $row), [
      'removed' => false,
      'shared_updated_at' => PartnerDocumentRepository::iso($row['partner_shared_updated_at']),
      'files' => PartnerDocumentRepository::sharedFiles($conexion, $row['id']),
    ]);
  }

  /* ------------------------------------------------------------------ outbound: reconcile */

  /**
   * Bring one quote's partner state in line with reality. Idempotent: calling it twice changes
   * nothing the second time. ctx: reason (for the unlink audit line).
   * Returns ['action' => none|shared|updated|restored|tombstoned].
   */
  public static function reconcile($conexion, $idRfq, array $ctx = []) {
    $row = PartnerShareRepository::row($conexion, $idRfq);
    if (!$row) return ['action' => 'none'];
    $nowUtc = self::utc();
    $isShared = (int)$row['share_with_partner'] === 1 && (int)$row['deleted'] === 0;

    if ($isShared) {
      $hash = self::payloadHash(self::buildPayload($conexion, $row));
      $restored = $row['partner_removed_at'] !== null;
      $first = $row['partner_shared_updated_at'] === null;
      $action = 'none';
      if ($restored || $first || $hash !== $row['partner_payload_hash']) {
        $conexion->prepare('UPDATE rfq SET partner_shared_updated_at = :t, partner_payload_hash = :h, partner_removed_at = NULL WHERE id = :id')
          ->execute([':t' => $nowUtc, ':h' => $hash, ':id' => $idRfq]);
        PartnerOutboxRepository::enqueue($conexion, $idRfq, 'opportunity.updated', self::noticePayload($row, 'opportunity.updated'));
        $action = $restored ? 'restored' : ($first ? 'shared' : 'updated');
      }
      PartnerDocumentRepository::reconcileShared($conexion, $idRfq);
      return ['action' => $action];
    }

    $everShared = $row['partner_shared_updated_at'] !== null || $row['partner_record_id'] !== null;
    if ($row['partner_removed_at'] === null && $everShared) {
      $wasLinked = $row['partner_record_id'] !== null;
      $conexion->prepare('UPDATE rfq SET partner_removed_at = :t, partner_record_id = NULL, partner_linked_at = NULL WHERE id = :id')
        ->execute([':t' => $nowUtc, ':id' => $idRfq]);
      PartnerOutboxRepository::enqueue($conexion, $idRfq, 'opportunity.removed', self::noticePayload($row, 'opportunity.removed'));
      self::audit($conexion, 'partner_unlink',
        '<b>Unlinked from partner</b> · ' . self::esc($ctx['reason'] ?? ((int)$row['deleted'] === 1 ? 'Quote deleted' : 'Share with partner turned off')),
        $idRfq, self::actor($conexion));
      return ['action' => 'tombstoned', 'was_linked' => $wasLinked];
    }
    return ['action' => 'none'];
  }

  /** Cron safety net: reconcile every quote that is shared or was shared and not yet tombstoned. */
  public static function scanAll($conexion) {
    $ids = $conexion->query('SELECT id FROM rfq
        WHERE (share_with_partner = 1 AND (deleted = 0 OR partner_removed_at IS NULL))
           OR (share_with_partner = 0 AND partner_removed_at IS NULL AND (partner_shared_updated_at IS NOT NULL OR partner_record_id IS NOT NULL))')
      ->fetchAll(PDO::FETCH_COLUMN);
    foreach ($ids as $id) self::reconcile($conexion, (int)$id);
    return count($ids);
  }

  /* ------------------------------------------------------------------ inbound */

  /** The partner's record in the one shape the rest of this class reads (`id` is accepted for `record_id`). */
  public static function normalizeIncoming(array $raw) {
    return [
      'record_id' => trim((string)($raw['record_id'] ?? $raw['id'] ?? '')),
      'solicitation_number' => trim((string)($raw['solicitation_number'] ?? '')),
      'name' => $raw['name'] ?? null,
      'agency' => $raw['agency'] ?? null,
      'issue_date' => $raw['issue_date'] ?? null,
      'end_date' => $raw['end_date'] ?? null,
      'status' => isset($raw['status']) ? strtolower(trim((string)$raw['status'])) : null,
      'assignee_email' => $raw['assignee_email'] ?? null,
      'shared_updated_at' => $raw['shared_updated_at'] ?? null,
      'removed' => !empty($raw['removed']),
      // null = "this notice did not carry a file list" (must not be read as "all files removed")
      'files' => isset($raw['files']) && is_array($raw['files']) ? $raw['files'] : null,
    ];
  }

  /** Our edit of "is this safe to hide": still Created, never priced, nothing attached to it. */
  private static function isUntouched($conexion, array $row) {
    if ((int)$row['status'] !== 0 || (int)$row['completado'] !== 0 || (int)$row['award'] !== 0
        || (int)$row['fullfillment'] !== 0 || (int)$row['invoice'] !== 0) return false;
    if ((float)$row['total_price'] !== 0.0) return false;
    if (!in_array((string)$row['comments'], ['', 'No comments'], true)) return false;
    if (!empty($row['sheet_row'])) return false;
    $stmt = $conexion->prepare('SELECT COUNT(*) FROM item WHERE id_rfq = :id');
    $stmt->execute([':id' => $row['id']]);
    return (int)$stmt->fetchColumn() === 0;
  }

  private static function isPostAward(array $row) {
    return (int)$row['award'] === 1 || (int)$row['fullfillment'] === 1 || (int)$row['invoice'] === 1 || (int)$row['submitted_invoice'] === 1;
  }

  /**
   * Apply an incoming record. Returns ['outcome' => created|linked|updated|ignored_stale|
   * ignored_deleted|ignored_unshared|review|rejected, 'id_rfq' => ?int].
   * opts: force (skip the conflict rule — a human chose this link).
   */
  public static function applyIncoming($conexion, array $rawRecord, array $opts = []) {
    $rec = self::normalizeIncoming($rawRecord);
    if ($rec['removed']) return self::applyRemoval($conexion, $rec['record_id'], $rec['solicitation_number']);
    if ($rec['record_id'] === '') return self::review($conexion, 'rejected', $rec, [], 'The partner record has no id');

    $pinned = PartnerShareRepository::findByRecordId($conexion, $rec['record_id']);
    if ($pinned) {
      if ((int)$pinned['deleted'] === 1) return ['outcome' => 'ignored_deleted', 'id_rfq' => (int)$pinned['id']];
      return self::applyToQuote($conexion, $pinned, $rec, $opts);
    }

    $key = PartnerShareRepository::normalizeKey($rec['solicitation_number']);
    if ($key === null) return self::review($conexion, 'rejected', $rec, [], 'The partner record has no Solicitation #');

    $matches = PartnerShareRepository::findByKey($conexion, $key);
    if (count($matches) === 1) {
      $m = $matches[0];
      if ($m['partner_record_id'] !== null && $m['partner_record_id'] !== $rec['record_id']) {
        return self::review($conexion, 'ambiguous', $rec, [(int)$m['id']], 'Already linked to another partner record');
      }
      $row = PartnerShareRepository::row($conexion, $m['id']);
      // The user explicitly stopped sharing this quote — a late partner notice must not undo that.
      if ($row['partner_removed_at'] !== null) return ['outcome' => 'ignored_unshared', 'id_rfq' => (int)$row['id']];
      return self::applyToQuote($conexion, $row, $rec, $opts);
    }
    if (count($matches) > 1) {
      return self::review($conexion, 'ambiguous', $rec, array_map('intval', array_column($matches, 'id')), 'Matches more than one quote');
    }

    $byCode = PartnerShareRepository::findUnkeyedByCode($conexion, $key);
    if ($byCode) {
      return self::review($conexion, count($byCode) === 1 ? 'possible' : 'ambiguous', $rec,
        array_map('intval', array_column($byCode, 'id')), 'Code equals an existing quote without a Solicitation #');
    }
    return self::createFromIncoming($conexion, $rec);
  }

  /** Upsert the open review row for this partner record (redelivery must not pile rows up). */
  private static function review($conexion, $kind, array $rec, array $candidates, $reason) {
    $payload = json_encode($rec);
    $stmt = $conexion->prepare('SELECT id FROM partner_review WHERE partner_record_id = :r AND resolved_at IS NULL LIMIT 1');
    $stmt->execute([':r' => $rec['record_id']]);
    $id = $stmt->fetchColumn();
    $params = [':k' => $kind, ':s' => $rec['solicitation_number'], ':c' => json_encode($candidates), ':p' => $payload, ':why' => $reason];
    if ($id) {
      $conexion->prepare('UPDATE partner_review SET kind = :k, solicitation_number = :s, candidates = :c, payload = :p, reason = :why WHERE id = :id')
        ->execute($params + [':id' => $id]);
    } else {
      $conexion->prepare('INSERT INTO partner_review (kind, partner_record_id, solicitation_number, candidates, payload, reason, created_at)
          VALUES (:k, :r, :s, :c, :p, :why, :t)')
        ->execute($params + [':r' => $rec['record_id'], ':t' => self::utc()]);
      $id = $conexion->lastInsertId();
    }
    return ['outcome' => $kind === 'rejected' ? 'rejected' : 'review', 'id_rfq' => null, 'review_id' => (int)$id];
  }

  private static function closeReview($conexion, $recordId, $resolution, $userId = null) {
    $conexion->prepare('UPDATE partner_review SET resolved_at = :t, resolution = :r, resolved_by = :u
        WHERE partner_record_id = :rec AND resolved_at IS NULL')
      ->execute([':t' => self::utc(), ':r' => $resolution, ':u' => $userId, ':rec' => $recordId]);
  }

  /** Link to / update an existing quote with a partner record. Shared fields only. */
  private static function applyToQuote($conexion, array $row, array $rec, array $opts = []) {
    $id = (int)$row['id'];
    $theirTs = self::isoToUtc($rec['shared_updated_at']) ?: self::utc();
    if (empty($opts['force']) && $row['partner_shared_updated_at'] !== null && $theirTs <= $row['partner_shared_updated_at']) {
      return ['outcome' => 'ignored_stale', 'id_rfq' => $id];
    }

    $set = []; $params = [':id' => $id]; $changed = [];
    $assign = function ($col, $label, $val) use (&$set, &$params, &$changed, $row) {
      if ($val === null || $val === '' || (string)$row[$col] === (string)$val) return;
      $set[] = "$col = :$col"; $params[":$col"] = $val; $changed[] = $label;
    };
    $assign('name', 'Description', is_string($rec['name']) ? trim($rec['name']) : null);
    $assign('client', 'Agency', is_string($rec['agency']) ? trim($rec['agency']) : null);
    $assign('issue_date', 'Issue Date', self::isoToLocal($rec['issue_date'], false));
    $assign('end_date', 'End Date', self::isoToLocal($rec['end_date'], true));
    if (isset(self::INBOUND_STATUS[$rec['status']]) && !self::isPostAward($row)) {
      $assign('comments', 'Status', self::INBOUND_STATUS[$rec['status']]);
    }
    if (trim((string)$row['solicitation_number']) === '' && $rec['solicitation_number'] !== '') {
      $set[] = 'solicitation_number = :sn'; $params[':sn'] = $rec['solicitation_number'];
      $set[] = 'solicitation_key = :sk'; $params[':sk'] = PartnerShareRepository::normalizeKey($rec['solicitation_number']);
    }
    $linking = $row['partner_record_id'] === null;
    if ($linking) {
      $set[] = 'partner_record_id = :pr'; $params[':pr'] = $rec['record_id'];
      $set[] = 'partner_linked_at = :pl'; $params[':pl'] = self::utc();
      $set[] = 'share_with_partner = 1';
      $set[] = 'partner_removed_at = NULL';
    }
    if ($set) $conexion->prepare('UPDATE rfq SET ' . implode(', ', $set) . ' WHERE id = :id')->execute($params);

    // Adopt THEIR timestamp (so our copy does not look newer than it is) and the hash of what we
    // now hold, so the next reconcile() sees no difference and queues nothing.
    $fresh = PartnerShareRepository::row($conexion, $id);
    $conexion->prepare('UPDATE rfq SET partner_shared_updated_at = :t, partner_payload_hash = :h WHERE id = :id')
      ->execute([':t' => $theirTs, ':h' => self::payloadHash(self::buildPayload($conexion, $fresh)), ':id' => $id]);

    $actor = self::partnerActor($conexion);
    if ($linking) {
      self::audit($conexion, 'partner_link', '<b>Linked to partner</b> · Matched on Solicitation # ' . self::esc($rec['solicitation_number']), $id, $actor);
    }
    if ($changed) {
      self::audit($conexion, 'partner_sync_in', '<b>Received from partner</b> · Opportunity updated · ' . self::esc(implode(', ', $changed)), $id, $actor);
    }
    self::ingestFiles($conexion, $id, $rec['files']);
    self::closeReview($conexion, $rec['record_id'], 'auto');
    return ['outcome' => $linking ? 'linked' : 'updated', 'id_rfq' => $id];
  }

  /** Create a brand-new quote from an incoming record. */
  private static function createFromIncoming($conexion, array $rec) {
    $serviceId = self::serviceUser($conexion);
    if ($serviceId === null) return self::review($conexion, 'rejected', $rec, [], 'Partner Integration user is missing — run the migration');

    $assignee = self::userIdByEmail($conexion, $rec['assignee_email']);
    $needsAssignment = 0;
    if ($assignee === null) {
      $default = self::$defaultAssigneeEmail ?? (defined('PARTNER_DEFAULT_ASSIGNEE_EMAIL') ? PARTNER_DEFAULT_ASSIGNEE_EMAIL : '');
      $assignee = self::userIdByEmail($conexion, $default);
      if ($assignee === null) return self::review($conexion, 'rejected', $rec, [], 'No default assignee configured');
      $needsAssignment = 1;
    }

    $number = $rec['solicitation_number'];
    $rfq = new Rfq([
      'id' => '', 'id_usuario' => $serviceId, 'usuario_designado' => $assignee,
      'canal' => defined('PARTNER_DEFAULT_CHANNEL') ? PARTNER_DEFAULT_CHANNEL : 'FBO',
      'email_code' => $number,
      'type_of_bid' => defined('PARTNER_DEFAULT_TYPE_OF_BID') ? PARTNER_DEFAULT_TYPE_OF_BID : 'Services',
      'issue_date' => (string)self::isoToLocal($rec['issue_date'], false),
      'end_date' => (string)self::isoToLocal($rec['end_date'], true),
      'status' => 0, 'completado' => 0, 'total_cost' => 0, 'total_price' => 0, 'comments' => 'No comments', 'award' => 0,
      'fecha_completado' => null, 'fecha_submitted' => null, 'fecha_award' => null, 'payment_terms' => 'Net 30',
      'address' => '', 'ship_to' => '', 'expiration_date' => null, 'ship_via' => '', 'taxes' => 0, 'profit' => 0,
      'additional' => 0, 'shipping' => '', 'shipping_cost' => 0, 'fullfillment' => 0, 'fulfillment_date' => null,
      'contract_number' => '', 'fulfillment_profit' => null, 'services_fulfillment_profit' => null,
      'total_fulfillment' => null, 'total_services_fulfillment' => null, 'invoice' => 0, 'invoice_date' => null,
      'multi_year_project' => null, 'submitted_invoice' => 0, 'submitted_invoice_date' => null,
      'fulfillment_pending' => 0, 'fulfillment_shipping_cost' => 0, 'fulfillment_shipping' => null,
      'type_of_contract' => null, 'net30_fulfillment' => null, 'sales_commission' => null, 'services_payment_term' => 'Net 30',
      'city' => null, 'zip_code' => null, 'state' => null, 'client' => is_string($rec['agency']) ? trim($rec['agency']) : null,
      'deleted' => 0, 'client_payment_terms' => 'Net 30', 'reference_url' => '', 'priority' => null,
      'name' => is_string($rec['name']) && trim($rec['name']) !== '' ? trim($rec['name']) : $number,
      // Never push a background-created quote to the SharePoint sheet; a user opts in with "Sync to Sheet".
      'sync_to_sheet' => 0,
    ]);
    list($inserted, $id) = RepositorioRfq::insertar_cotizacion($conexion, $rfq);
    if (!$inserted || !$id) return self::review($conexion, 'rejected', $rec, [], 'The quote could not be created');
    $id = (int)$id;

    $theirTs = self::isoToUtc($rec['shared_updated_at']) ?: self::utc();
    $conexion->prepare('UPDATE rfq SET share_with_partner = 1, solicitation_number = :n, solicitation_key = :k, partner_record_id = :r,
        partner_linked_at = :l, partner_shared_updated_at = :t, partner_needs_assignment = :na WHERE id = :id')
      ->execute([':n' => $number, ':k' => PartnerShareRepository::normalizeKey($number), ':r' => $rec['record_id'],
                 ':l' => self::utc(), ':t' => $theirTs, ':na' => $needsAssignment, ':id' => $id]);
    $fresh = PartnerShareRepository::row($conexion, $id);
    $conexion->prepare('UPDATE rfq SET partner_payload_hash = :h WHERE id = :id')
      ->execute([':h' => self::payloadHash(self::buildPayload($conexion, $fresh)), ':id' => $id]);

    $actor = self::partnerActor($conexion);
    self::audit($conexion, 'quote_created', 'The quote was <b>Created</b>', $id, $actor);
    self::audit($conexion, 'partner_sync_in', '<b>Received from partner</b> · Quote created · Solicitation # ' . self::esc($number), $id, $actor);
    self::ingestFiles($conexion, $id, $rec['files']);
    self::closeReview($conexion, $rec['record_id'], 'auto');
    return ['outcome' => 'created', 'id_rfq' => $id];
  }

  /**
   * The partner stopped sharing / deleted the record: detach, stop syncing, and hide the quote
   * only while it is still untouched. Never queues a notice back.
   */
  public static function applyRemoval($conexion, $recordId, $solicitationNumber = '') {
    $row = $recordId !== '' ? PartnerShareRepository::findByRecordId($conexion, $recordId) : null;
    if (!$row) return ['outcome' => 'ignored_unknown', 'id_rfq' => null];
    $id = (int)$row['id'];
    $hide = (int)$row['deleted'] === 0 && self::isUntouched($conexion, $row);
    $conexion->prepare('UPDATE rfq SET partner_record_id = NULL, partner_linked_at = NULL, share_with_partner = 0,
        partner_needs_assignment = 0, partner_removed_at = :t' . ($hide ? ', deleted = 1' : '') . ' WHERE id = :id')
      ->execute([':t' => self::utc(), ':id' => $id]);
    self::audit($conexion, 'partner_removed',
      '<b>Removed from partner</b> · ' . ($hide ? 'Quote hidden (it was untouched)' : 'Link detached, quote kept'),
      $id, self::partnerActor($conexion));
    self::closeReview($conexion, $recordId, 'removed');
    return ['outcome' => $hide ? 'hidden' : 'detached', 'id_rfq' => $id];
  }

  /* ------------------------------------------------------------------ inbound files */

  private static function ingestFiles($conexion, $idRfq, $files) {
    if (!is_array($files)) return;
    $settings = PartnerIntegrationRepository::get($conexion);
    $baseHost = parse_url((string)$settings['partner_base_url'], PHP_URL_HOST);
    $dir = PartnerDocumentRepository::folder($idRfq);
    $seen = [];
    $actor = self::partnerActor($conexion);
    foreach ($files as $f) {
      if (!is_array($f) || empty($f['id']) || empty($f['name'])) continue;
      $name = PartnerDocumentRepository::safeName(Input::sanitize_filename($f['name']));
      if ($name === null) continue;
      $partnerFileId = (string)$f['id'];
      $seen[$partnerFileId] = true;

      $existing = PartnerDocumentRepository::find($conexion, $idRfq, $name);
      if ($existing && $existing['partner_file_id'] !== $partnerFileId && is_file("$dir/$name")) {
        $name = 'partner_' . $name; // never overwrite a file that is ours
        $existing = PartnerDocumentRepository::find($conexion, $idRfq, $name);
      }
      if ($existing && $existing['partner_file_id'] === $partnerFileId && $existing['removed_at'] === null
          && (empty($f['sha256']) || $existing['sha256'] === $f['sha256']) && is_file("$dir/$name")) continue;

      // Only ever send the partner credential to the partner's own host.
      $url = (string)($f['download_url'] ?? '');
      if ($baseHost === null || $baseHost === false || parse_url($url, PHP_URL_HOST) !== $baseHost) continue;
      $resp = self::request('GET', $url, self::partnerHeaders($settings), null, 30);
      if ($resp['status'] < 200 || $resp['status'] > 299 || $resp['body'] === '' || strlen($resp['body']) > self::MAX_DOWNLOAD_BYTES) continue;
      $sha = hash('sha256', $resp['body']);
      if (!empty($f['sha256']) && !hash_equals(strtolower($f['sha256']), $sha)) continue;
      if (!is_dir($dir) && !@mkdir($dir, 0777, true)) continue;
      if (file_put_contents("$dir/$name", $resp['body']) === false) continue;

      $conexion->prepare('INSERT INTO partner_documents (id_rfq, file_name, shared, partner_file_id, size_bytes, sha256, shared_updated_at, removed_at)
          VALUES (:r, :n, 1, :p, :z, :h, :t, NULL)
          ON DUPLICATE KEY UPDATE shared = 1, partner_file_id = :p, size_bytes = :z, sha256 = :h, shared_updated_at = :t, removed_at = NULL')
        ->execute([':r' => $idRfq, ':n' => $name, ':p' => $partnerFileId, ':z' => strlen($resp['body']), ':h' => $sha, ':t' => self::utc()]);
      self::audit($conexion, 'partner_sync_in', '<b>Received from partner</b> · Document · ' . self::esc($name), $idRfq, $actor);
    }
    // A full file list that no longer names a partner file means they unshared it. Keep our copy.
    $stmt = $conexion->prepare('SELECT id, partner_file_id FROM partner_documents WHERE id_rfq = :r AND partner_file_id IS NOT NULL AND removed_at IS NULL');
    $stmt->execute([':r' => $idRfq]);
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $d) {
      if (!isset($seen[$d['partner_file_id']])) {
        $conexion->prepare('UPDATE partner_documents SET removed_at = :t WHERE id = :id')->execute([':t' => self::utc(), ':id' => $d['id']]);
      }
    }
  }

  /* ------------------------------------------------------------------ review queue */

  /**
   * An admin decision on a held record. action: 'link' (to $quoteId, default the single candidate)
   * or 'create'. A human chose, so the conflict rule is skipped for a link.
   */
  public static function resolveReview($conexion, $reviewId, $action, $userId, $quoteId = null) {
    $stmt = $conexion->prepare('SELECT * FROM partner_review WHERE id = :id AND resolved_at IS NULL');
    $stmt->execute([':id' => $reviewId]);
    $rv = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$rv) return ['outcome' => 'not_found', 'id_rfq' => null];
    $rec = json_decode($rv['payload'], true) ?: [];
    $candidates = array_map('intval', json_decode($rv['candidates'] ?: '[]', true) ?: []);

    if ($action === 'create') {
      $res = self::createFromIncoming($conexion, $rec);
    } else {
      $quoteId = $quoteId ?: (count($candidates) === 1 ? $candidates[0] : null);
      $row = $quoteId ? PartnerShareRepository::row($conexion, $quoteId) : null;
      if (!$row || (int)$row['deleted'] === 1 || !in_array((int)$quoteId, $candidates, true)
          || ($row['partner_record_id'] !== null && $row['partner_record_id'] !== $rec['record_id'])) {
        return ['outcome' => 'invalid', 'id_rfq' => null];
      }
      $res = self::applyToQuote($conexion, $row, $rec, ['force' => true]);
    }
    if (in_array($res['outcome'], ['created', 'linked', 'updated'], true)) {
      $conexion->prepare('UPDATE partner_review SET resolved_at = :t, resolved_by = :u, resolution = :r WHERE id = :id')
        ->execute([':t' => self::utc(), ':u' => $userId, ':r' => $action, ':id' => $reviewId]);
    }
    return $res;
  }

  /** Open review rows for the admin card, with the candidate quotes' display facts. */
  public static function openReviews($conexion) {
    $rows = $conexion->query('SELECT * FROM partner_review WHERE resolved_at IS NULL ORDER BY created_at DESC, id DESC LIMIT 200')
      ->fetchAll(PDO::FETCH_ASSOC);
    $q = $conexion->prepare('SELECT id, name, email_code FROM rfq WHERE id = :id');
    foreach ($rows as &$r) {
      $r['incoming'] = json_decode($r['payload'], true) ?: [];
      $r['candidate_quotes'] = [];
      foreach (json_decode($r['candidates'] ?: '[]', true) ?: [] as $cid) {
        $q->execute([':id' => $cid]);
        if ($c = $q->fetch(PDO::FETCH_ASSOC)) $r['candidate_quotes'][] = $c;
      }
    }
    return $rows;
  }

  /** Re-run held "rejected" records (e.g. after the default assignee was fixed). */
  public static function reprocessRejected($conexion) {
    $rows = $conexion->query("SELECT payload FROM partner_review WHERE kind = 'rejected' AND resolved_at IS NULL ORDER BY id LIMIT 50")
      ->fetchAll(PDO::FETCH_COLUMN);
    $applied = 0;
    foreach ($rows as $payload) {
      $res = self::applyIncoming($conexion, json_decode($payload, true) ?: []);
      if (in_array($res['outcome'], ['created', 'linked', 'updated'], true)) $applied++;
    }
    return $applied;
  }

  /* ------------------------------------------------------------------ signatures + HTTP */

  public static function sign($secret, $timestamp, $body) {
    return hash_hmac('sha256', $timestamp . '.' . $body, $secret);
  }

  /** HMAC over "{timestamp}.{rawBody}", constant-time, and the timestamp must be within 5 minutes. */
  public static function verifySignature($secret, $timestamp, $rawBody, $signature, $nowTs = null) {
    if ($secret === null || $secret === '' || !ctype_digit((string)$timestamp) || !is_string($signature)) return false;
    if (abs(($nowTs ?: time()) - (int)$timestamp) > self::SIGNATURE_WINDOW) return false;
    return hash_equals(self::sign($secret, (string)$timestamp, $rawBody), strtolower($signature));
  }

  private static function partnerHeaders(array $settings) {
    $h = ['Accept' => 'application/json'];
    if (!empty($settings['partner_token'])) $h['Authorization'] = 'Bearer ' . $settings['partner_token'];
    return $h;
  }

  /** @return array ['status' => int, 'body' => string, 'error' => ?string] */
  public static function request($method, $url, array $headers, $body = null, $timeout = 10) {
    if (self::$http) return call_user_func(self::$http, $method, $url, $headers, $body, $timeout);
    if (!in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true)) {
      return ['status' => 0, 'body' => '', 'error' => 'Unsupported URL'];
    }
    $lines = [];
    foreach ($headers as $k => $v) $lines[] = "$k: $v";
    $ctx = stream_context_create(['http' => [
      'method' => $method, 'header' => implode("\r\n", $lines), 'content' => $body, 'timeout' => $timeout, 'ignore_errors' => true,
    ]]);
    $resp = @file_get_contents($url, false, $ctx, 0, self::MAX_DOWNLOAD_BYTES + 1);
    $status = 0;
    if (isset($http_response_header[0]) && preg_match('#\s(\d{3})(\s|$)#', $http_response_header[0], $m)) $status = (int)$m[1];
    if ($resp === false) return ['status' => 0, 'body' => '', 'error' => 'Connection failed or timed out after ' . $timeout . ' s'];
    return ['status' => $status, 'body' => $resp, 'error' => null];
  }

  /* ------------------------------------------------------------------ dispatch + pull */

  /**
   * Exclusive, non-blocking run lock for a cron job. Returns the handle (keep it alive for the
   * whole run) or null when another run holds it. Throws when the lock file cannot be opened —
   * that is a setup problem and must not be reported as "another run is in progress".
   * The file is made world-writable so the cron user and a person running the job by hand share it.
   */
  public static function acquireLock($name) {
    $path = sys_get_temp_dir() . '/' . $name . '.lock';
    $h = @fopen($path, 'c');
    if (!$h) throw new RuntimeException("Cannot open lock file $path — check its owner and permissions.");
    @chmod($path, 0666);
    return flock($h, LOCK_EX | LOCK_NB) ? $h : null;
  }

  /** Drain the outbox. Returns ['sent' => n, 'retry' => n, 'failed' => n, 'skipped' => bool]. */
  public static function dispatchDue($conexion, $nowTs = null) {
    $now = $nowTs ?: time();
    $s = PartnerIntegrationRepository::get($conexion);
    $out = ['sent' => 0, 'retry' => 0, 'failed' => 0, 'skipped' => false];
    if (!self::canDeliver($s)) {
      $out['skipped'] = true;
      return $out;
    }
    foreach (PartnerOutboxRepository::due($conexion, $now) as $row) {
      $res = self::deliver($conexion, $s, $row, $now);
      $out[$res === 'delivered' ? 'sent' : $res]++;
    }
    return $out;
  }

  private static function canDeliver(array $s) {
    return (int)$s['enabled'] === 1 && !empty($s['partner_webhook_url']) && !empty($s['signing_secret']);
  }

  /** One signed POST of an outbox row, recorded either way. Returns 'delivered' | 'retry' | 'failed'. */
  private static function deliver($conexion, array $s, array $row, $now) {
    $headers = ['Content-Type' => 'application/json', 'Accept' => 'application/json',
                'X-Event' => $row['event'], 'X-Timestamp' => (string)$now,
                'X-Signature' => self::sign($s['signing_secret'], (string)$now, $row['payload'])];
    if (!empty($s['partner_token'])) $headers['Authorization'] = 'Bearer ' . $s['partner_token'];
    $r = self::request('POST', $s['partner_webhook_url'], $headers, $row['payload'], 10);
    $ok = $r['status'] >= 200 && $r['status'] <= 299;
    $err = $ok ? null : ($r['error'] ?: 'HTTP ' . $r['status'] . ' — partner service did not accept the notice');
    $res = PartnerOutboxRepository::recordResult($conexion, $row, $ok, $err, $now);
    if ($ok) PartnerIntegrationRepository::markWebhookSuccess($conexion, self::utc($now));
    return $res;
  }

  /** One immediate attempt for a parked row (admin Retry). Returns 'delivered' | 'retry' | 'failed' | 'skipped'. */
  public static function retryNow($conexion, $outboxId) {
    $now = time();
    $s = PartnerIntegrationRepository::get($conexion);
    if (!self::canDeliver($s)) return 'skipped';
    PartnerOutboxRepository::retry($conexion, $outboxId, $now);
    $row = PartnerOutboxRepository::find($conexion, $outboxId);
    if (!$row || $row['delivered_at'] !== null || $row['failed_at'] !== null) return 'skipped';
    return self::deliver($conexion, $s, $row, $now);
  }

  /** Fetch one record from the partner (webhook follow-up). Returns the decoded record or null. */
  public static function fetchRecord($conexion, $recordId, $solicitationNumber) {
    $s = PartnerIntegrationRepository::get($conexion);
    if (empty($s['partner_base_url'])) return null;
    $ref = $recordId !== '' ? $recordId : $solicitationNumber;
    $r = self::request('GET', $s['partner_base_url'] . '/opportunities/' . rawurlencode($ref), self::partnerHeaders($s), null, 10);
    if ($r['status'] < 200 || $r['status'] > 299) return null;
    $json = json_decode($r['body'], true);
    if (!is_array($json)) return null;
    return isset($json['data']) && is_array($json['data']) ? $json['data'] : $json;
  }

  /**
   * Catch-up: pull everything the partner changed since last_pull_at and apply it. A first-ever
   * pull starts at enable time (set by saveSettings), so turning the integration on never floods
   * the app with the partner's whole history.
   */
  public static function pull($conexion) {
    $s = PartnerIntegrationRepository::get($conexion);
    $out = ['applied' => 0, 'seen' => 0, 'skipped' => false];
    if ((int)$s['enabled'] !== 1 || empty($s['partner_base_url'])) { $out['skipped'] = true; return $out; }
    $startedAt = time();
    $since = $s['last_pull_at'] ? gmdate('Y-m-d\TH:i:s\Z', strtotime($s['last_pull_at'] . ' UTC')) : gmdate('Y-m-d\TH:i:s\Z', $startedAt);
    $cursor = null;
    for ($page = 0; $page < 20; $page++) {
      $url = $s['partner_base_url'] . '/opportunities?changed_since=' . rawurlencode($since) . '&limit=100'
           . ($cursor ? '&cursor=' . rawurlencode($cursor) : '');
      $r = self::request('GET', $url, self::partnerHeaders($s), null, 20);
      if ($r['status'] < 200 || $r['status'] > 299) { $out['error'] = $r['error'] ?: 'HTTP ' . $r['status']; return $out; }
      $json = json_decode($r['body'], true);
      if (!is_array($json)) { $out['error'] = 'Unreadable response'; return $out; }
      foreach ($json['data'] ?? [] as $raw) {
        $out['seen']++;
        try {
          $res = self::applyIncoming($conexion, is_array($raw) ? $raw : []);
          if (in_array($res['outcome'], ['created', 'linked', 'updated', 'hidden', 'detached'], true)) $out['applied']++;
        } catch (Throwable $e) {
          error_log('Partner pull: record failed — ' . $e->getMessage());
        }
      }
      $cursor = $json['next_cursor'] ?? null;
      if (!$cursor) break;
    }
    // Overlap by a few seconds: re-applying a record is a no-op under the conflict rule.
    PartnerIntegrationRepository::setLastPull($conexion, self::utc($startedAt - 5));
    $out['applied'] += self::reprocessRejected($conexion);
    return $out;
  }
}
