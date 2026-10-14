<?php

/**
 * PartnerApi
 *
 * The partner-facing HTTP surface, as a pure function of (method, path, query, headers, body) so it
 * is testable without a web server; scripts/api/partner.php is the thin wrapper that feeds it and
 * emits the result.
 *
 *   GET  api/partner/opportunities?changed_since=&cursor=&limit=   shared + removed, paged, stable order
 *   GET  api/partner/opportunities/{solicitation_number}           one opportunity + its shared files
 *   GET  api/partner/files/{file_id}                               authenticated download
 *   POST api/partner/webhook                                       inbound light notice (token + HMAC)
 *
 * Every route needs `Authorization: Bearer <issued token>`. A failure is a BARE 401 — no body, and
 * the same answer for "no token", "wrong token", "revoked" and "integration disabled".
 */
class PartnerApi {

  const DEFAULT_LIMIT = 100;
  const MAX_LIMIT = 500;
  const FLOOR = '1000-01-01 00:00:00';

  /** @return array ['status' => int, 'json' => ?array, 'file' => ?string, 'name' => ?string] */
  public static function handle($conexion, $method, array $segments, array $query, array $headers, $rawBody, $nowTs = null) {
    $headers = array_change_key_case($headers, CASE_LOWER);
    $method = strtoupper($method);

    if (!PartnerIntegrationRepository::verifyBearer($conexion, $headers['authorization'] ?? null)) {
      return self::out(401);
    }
    if (($segments[0] ?? '') !== 'partner') return self::out(404, ['error' => 'not_found']);

    switch ($segments[1] ?? '') {
      case 'opportunities':
        if ($method !== 'GET') return self::out(405, ['error' => 'method_not_allowed']);
        if (isset($segments[2]) && $segments[2] !== '') return self::one($conexion, urldecode($segments[2]));
        if (!empty($query['solicitation_number'])) return self::one($conexion, (string)$query['solicitation_number']);
        return self::changedSince($conexion, $query);

      case 'files':
        if ($method !== 'GET') return self::out(405, ['error' => 'method_not_allowed']);
        $file = ctype_digit((string)($segments[2] ?? '')) ? PartnerDocumentRepository::downloadable($conexion, (int)$segments[2]) : null;
        return $file ? ['status' => 200, 'json' => null, 'file' => $file['path'], 'name' => $file['name']] : self::out(404, ['error' => 'not_found']);

      case 'webhook':
        if ($method !== 'POST') return self::out(405, ['error' => 'method_not_allowed']);
        return self::webhook($conexion, $headers, (string)$rawBody, $nowTs);
    }
    return self::out(404, ['error' => 'not_found']);
  }

  private static function out($status, $json = null) {
    return ['status' => $status, 'json' => $json, 'file' => null, 'name' => null];
  }

  private static function one($conexion, $solicitationNumber) {
    $key = PartnerShareRepository::normalizeKey($solicitationNumber);
    if ($key === null) return self::out(404, ['error' => 'not_found']);
    $stmt = $conexion->prepare('SELECT id FROM rfq WHERE solicitation_key = :k
        AND ((share_with_partner = 1 AND deleted = 0) OR partner_removed_at IS NOT NULL)
        ORDER BY (share_with_partner = 1 AND deleted = 0) DESC, id LIMIT 1');
    $stmt->execute([':k' => $key]);
    $id = $stmt->fetchColumn();
    if ($id === false) return self::out(404, ['error' => 'not_found']);
    return self::out(200, PartnerSyncService::opportunityJson($conexion, PartnerShareRepository::row($conexion, $id)));
  }

  private static function changedSince($conexion, array $query) {
    $since = PartnerSyncService::isoToUtc($query['changed_since'] ?? '1970-01-02T00:00:00Z');
    if ($since === null) return self::out(400, ['error' => 'invalid_changed_since']);
    $limit = max(1, min(self::MAX_LIMIT, (int)($query['limit'] ?? self::DEFAULT_LIMIT)));

    $cursorTs = self::FLOOR; $cursorId = 0;
    if (!empty($query['cursor'])) {
      $c = json_decode((string)base64_decode(strtr($query['cursor'], '-_', '+/'), true), true);
      if (!is_array($c) || count($c) !== 2) return self::out(400, ['error' => 'invalid_cursor']);
      list($cursorTs, $cursorId) = [(string)$c[0], (int)$c[1]];
    }

    // changed_at = the latest of: last shared-field save, tombstone, last file change.
    $stmt = $conexion->prepare('SELECT t.id, t.changed_at FROM (
        SELECT r.id, GREATEST(
            COALESCE(r.partner_shared_updated_at, :floor1),
            COALESCE(r.partner_removed_at, :floor2),
            COALESCE(d.doc_changed, :floor3)) AS changed_at
        FROM rfq r
        LEFT JOIN (SELECT id_rfq, MAX(GREATEST(COALESCE(shared_updated_at, :floor4), COALESCE(removed_at, :floor5))) AS doc_changed
                   FROM partner_documents WHERE partner_file_id IS NULL GROUP BY id_rfq) d ON d.id_rfq = r.id
        WHERE (r.share_with_partner = 1 AND r.deleted = 0) OR r.partner_removed_at IS NOT NULL
      ) t
      WHERE t.changed_at > :since AND (t.changed_at > :cts OR (t.changed_at = :cts2 AND t.id > :cid))
      ORDER BY t.changed_at ASC, t.id ASC LIMIT ' . ($limit + 1));
    $stmt->execute([':floor1' => self::FLOOR, ':floor2' => self::FLOOR, ':floor3' => self::FLOOR, ':floor4' => self::FLOOR,
                    ':floor5' => self::FLOOR, ':since' => $since, ':cts' => $cursorTs, ':cts2' => $cursorTs, ':cid' => $cursorId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $more = count($rows) > $limit;
    $rows = array_slice($rows, 0, $limit);
    $data = [];
    foreach ($rows as $r) {
      $data[] = PartnerSyncService::opportunityJson($conexion, PartnerShareRepository::row($conexion, $r['id']));
    }
    $next = null;
    if ($more && $rows) {
      $last = end($rows);
      $next = rtrim(strtr(base64_encode(json_encode([$last['changed_at'], (int)$last['id']])), '+/', '-_'), '=');
    }
    return self::out(200, ['data' => $data, 'next_cursor' => $next, 'server_time' => gmdate('Y-m-d\TH:i:s\Z')]);
  }

  private static function webhook($conexion, array $headers, $rawBody, $nowTs) {
    $secret = PartnerIntegrationRepository::get($conexion)['signing_secret'];
    if (!PartnerSyncService::verifySignature($secret, $headers['x-timestamp'] ?? '', $rawBody, $headers['x-signature'] ?? '', $nowTs)) {
      return self::out(401);
    }
    $notice = json_decode($rawBody, true);
    if (!is_array($notice) || empty($notice['event']) || !is_string($notice['event'])) {
      return self::out(400, ['error' => 'invalid_notice']);
    }
    $recordId = trim((string)($notice['record_id'] ?? ''));
    $number = trim((string)($notice['solicitation_number'] ?? ''));

    if ($notice['event'] === 'opportunity.removed') {
      $res = PartnerSyncService::applyRemoval($conexion, $recordId, $number);
      return self::out(200, ['ok' => true, 'outcome' => $res['outcome']]);
    }
    // Light notice: the fetch is authoritative. If it fails, say so — the partner retries, and the
    // changed-since pull is the backstop either way.
    $record = PartnerSyncService::fetchRecord($conexion, $recordId, $number);
    if ($record === null) return self::out(502, ['error' => 'fetch_failed']);
    $res = PartnerSyncService::applyIncoming($conexion, $record);
    return self::out(200, ['ok' => true, 'outcome' => $res['outcome']]);
  }
}
