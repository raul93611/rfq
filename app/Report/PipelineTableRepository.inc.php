<?php

/**
 * PipelineTableRepository
 *
 * Server-side data for the Bid Pipeline Metrics "Table" view — a dense, filterable,
 * paginated list of quotes over the same cohort (rfq.created_at) the charts use.
 *
 * Reuses PipelineMetricsRepository's public building blocks so the derived status,
 * dollar value, and status vocabulary always agree with the charts:
 *   STATUS_CASE, STATUSES, SERVICES_JOIN, VALUE_EXPR.
 *
 * Aggregation/paging is entirely in SQL — never by loading Rfq objects. Filters are
 * AND-combined; an empty filter set returns every non-deleted quote in the period.
 * Inverted custom date ranges simply return no rows (no error).
 */
class PipelineTableRepository {

  const PAGE_SIZE = 25;

  /**
   * One page of rows for a period + filters, plus the total count.
   * @return array ['rows' => [...], 'total' => int, 'page' => int, 'pageSize' => int]
   */
  public static function getPage($conexion, array $period, array $filters, $currentUserId, $page = 0) {
    $page = max(0, (int)$page);
    [$innerWhere, $statusWhere, $params] = self::buildWhere($period, $filters);

    $countSql = "SELECT COUNT(*) FROM (
        SELECT " . PipelineMetricsRepository::STATUS_CASE . " AS bucket
        FROM rfq
        WHERE $innerWhere
      ) t" . ($statusWhere ? " WHERE $statusWhere" : "");
    $total = (int)self::scalar($conexion, $countSql, $params);

    $offset = $page * self::PAGE_SIZE;
    $rowsSql = "SELECT id, email_code, canal, type_of_bid, name, designated_username, value, bucket, watched, created_at
      FROM (
        SELECT rfq.id, rfq.email_code, rfq.canal, rfq.type_of_bid, rfq.name, rfq.created_at,
               u.nombre_usuario AS designated_username,
               " . PipelineMetricsRepository::VALUE_EXPR . " AS value,
               " . PipelineMetricsRepository::STATUS_CASE . " AS bucket,
               CASE WHEN qw.id IS NULL THEN 0 ELSE 1 END AS watched
        FROM rfq" . PipelineMetricsRepository::SERVICES_JOIN . "
        LEFT JOIN usuarios u ON u.id = rfq.usuario_designado
        LEFT JOIN quote_watchers qw ON qw.id_rfq = rfq.id AND qw.id_user = :cu
        WHERE $innerWhere
      ) t
      " . ($statusWhere ? "WHERE $statusWhere" : "") . "
      ORDER BY id DESC
      LIMIT $offset, " . self::PAGE_SIZE;

    $params[':cu'] = (int)$currentUserId;
    $rows = self::run($conexion, $rowsSql, $params);

    $labels = []; $colors = [];
    foreach (PipelineMetricsRepository::STATUSES as $s) { $labels[$s['key']] = $s['label']; $colors[$s['key']] = $s['color']; }

    $out = array_map(function ($r) use ($labels, $colors) {
      $name = trim(preg_replace('/\s+/', ' ', (string)$r['name']));
      return [
        'id'          => (int)$r['id'],
        'code'        => $r['email_code'] !== '' && $r['email_code'] !== null ? $r['email_code'] : ('RFQ-' . $r['id']),
        'emailCode'   => $r['email_code'] !== '' && $r['email_code'] !== null ? $r['email_code'] : '—',
        'channel'     => self::displayChannel($r['canal']),
        'bidType'     => $r['type_of_bid'] !== '' && $r['type_of_bid'] !== null ? $r['type_of_bid'] : 'Uncategorized',
        'user'        => $r['designated_username'] !== null ? $r['designated_username'] : 'Unassigned',
        'status'      => $r['bucket'],
        'statusLabel' => $labels[$r['bucket']] ?? $r['bucket'],
        'statusColor' => $colors[$r['bucket']] ?? '#9aa6b6',
        'name'        => $name !== '' ? $name : ('Proposal #' . $r['id']),
        'value'       => (float)$r['value'],
        'watched'     => (int)$r['watched'] === 1,
        'created'     => !empty($r['created_at']) ? date('m/d/Y', strtotime($r['created_at'])) : '—',
      ];
    }, $rows);

    return ['rows' => $out, 'total' => $total, 'page' => $page, 'pageSize' => self::PAGE_SIZE];
  }

  /** Distinct raw channels present in the data, for the Channel filter dropdown. */
  public static function getDistinctChannels($conexion) {
    $sql = "SELECT DISTINCT canal FROM rfq WHERE deleted = 0 AND canal IS NOT NULL AND canal <> '' ORDER BY canal ASC";
    $rows = self::run($conexion, $sql, []);
    return array_map(fn($r) => ['value' => $r['canal'], 'label' => self::displayChannel($r['canal'])], $rows);
  }

  /** Mirrors Rfq::print_channel() for the two renamed channels. */
  private static function displayChannel($canal) {
    if ($canal === 'FedBid') return 'Unison';
    if ($canal === 'FBO') return 'SAM';
    return (string)$canal;
  }

  /**
   * Builds the inner (rfq-level) WHERE and the outer (derived-status) WHERE + params.
   * @return array [innerWhere, statusWhere, params]
   */
  private static function buildWhere(array $period, array $filters) {
    [$periodSql, $params] = self::periodClause($period);
    $inner = ["rfq.deleted = 0", $periodSql];

    if (!empty($filters['quoteId'])) {
      $params[':qid'] = '%' . $filters['quoteId'] . '%';
      $inner[] = "CAST(rfq.id AS CHAR) LIKE :qid";
    }
    if (!empty($filters['channel'])) {
      $params[':channel'] = $filters['channel'];
      $inner[] = "rfq.canal = :channel";
    }
    if (!empty($filters['emailCode'])) {
      $params[':ec'] = '%' . $filters['emailCode'] . '%';
      $inner[] = "rfq.email_code LIKE :ec";
    }
    if (!empty($filters['bidType'])) {
      $params[':bt'] = $filters['bidType'];
      $inner[] = "rfq.type_of_bid = :bt";
    }
    if (!empty($filters['user'])) {
      $params[':uid'] = (int)$filters['user'];
      $inner[] = "rfq.usuario_designado = :uid";
    }

    $statusWhere = '';
    if (!empty($filters['statuses']) && is_array($filters['statuses'])) {
      $validKeys = array_column(PipelineMetricsRepository::STATUSES, 'key');
      $keys = array_values(array_intersect($filters['statuses'], $validKeys));
      if (!empty($keys)) {
        $ph = [];
        foreach ($keys as $i => $k) { $ph[] = ":st$i"; $params[":st$i"] = $k; }
        $statusWhere = "bucket IN (" . implode(',', $ph) . ")";
      }
    }

    return [implode(' AND ', $inner), $statusWhere, $params];
  }

  /** Period WHERE fragment on rfq.created_at. Adds a custom from/to range for the table. */
  private static function periodClause(array $period) {
    $col = "rfq.created_at";
    $mode = $period['mode'] ?? 'year';

    if ($mode === 'custom') {
      $sql = "$col IS NOT NULL AND DATE($col) BETWEEN :cfrom AND :cto";
      return [$sql, [':cfrom' => (string)$period['from'], ':cto' => (string)$period['to']]];
    }

    $sql = "$col IS NOT NULL AND YEAR($col) = :year";
    $params = [':year' => (int)$period['year']];
    if ($mode === 'quarter') {
      $sql .= " AND QUARTER($col) = :quarter";
      $params[':quarter'] = (int)$period['quarter'];
    } elseif ($mode === 'month') {
      $sql .= " AND MONTH($col) = :month";
      $params[':month'] = (int)$period['month'];
    }
    return [$sql, $params];
  }

  private static function scalar($conexion, $sql, array $params) {
    $stmt = $conexion->prepare($sql);
    foreach ($params as $n => $v) { $stmt->bindValue($n, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR); }
    $stmt->execute();
    return $stmt->fetchColumn();
  }

  private static function run($conexion, $sql, array $params) {
    $stmt = $conexion->prepare($sql);
    foreach ($params as $n => $v) { $stmt->bindValue($n, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR); }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
