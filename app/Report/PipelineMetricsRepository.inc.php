<?php

/**
 * PipelineMetricsRepository
 *
 * Aggregations behind the Bid Pipeline Metrics dashboard. Every number is computed
 * in SQL over the live rfq table — never by loading Rfq objects into PHP.
 *
 * The derived "status bucket" is a CASE expression that mirrors, exactly and in order,
 * Rfq::getSheetStatus(). Keep the two in sync.
 *
 * issue_date is a VARCHAR in MM/DD/YYYY form, so the period filter parses it with
 * STR_TO_DATE(...,'%m/%d/%Y'); unparseable rows return NULL and fall out of any
 * period-scoped view (intended — see feature spec).
 */
class PipelineMetricsRepository {

  /** SQL CASE that derives the pipeline bucket. Mirrors Rfq::getSheetStatus(). */
  const STATUS_CASE = "
    CASE
      WHEN (rfq.award = 1 OR rfq.fullfillment = 1 OR rfq.invoice = 1 OR rfq.submitted_invoice = 1) THEN 'award'
      WHEN rfq.comments = 'No Award - Pricing' THEN 'no_award_pricing'
      WHEN rfq.comments = 'No Award - Technical' THEN 'no_award_technical'
      WHEN rfq.status = 1 AND rfq.sources_sought = 1 THEN 'submitted_ss'
      WHEN rfq.status = 1 THEN 'submitted'
      WHEN rfq.comments = 'Not submitted' THEN 'not_submitted'
      WHEN rfq.comments = 'Cancelled' THEN 'cancelled'
      WHEN rfq.comments IN ('No Bid','Manufacturer in the Bid','Expired due date','Supplier did not provide a quote','Others') THEN 'no_bid'
      WHEN rfq.completado = 1 THEN 'bid'
      ELSE 'tbd'
    END";

  /** Ordered status vocabulary — the 10 buckets, with display label + chart color. */
  const STATUSES = [
    ['key' => 'tbd',                'label' => 'TBD',                        'color' => '#9aa6b6'],
    ['key' => 'bid',                'label' => 'Bid',                        'color' => '#2db4e8'],
    ['key' => 'no_bid',             'label' => 'No Bid',                     'color' => '#7d8ba0'],
    ['key' => 'submitted',          'label' => 'Submitted',                  'color' => '#4f6ef0'],
    ['key' => 'submitted_ss',       'label' => 'Submitted — Sources Sought', 'color' => '#14b8a6'],
    ['key' => 'award',              'label' => 'Award',                      'color' => '#16a34a'],
    ['key' => 'no_award_pricing',   'label' => 'No Award — Pricing',         'color' => '#dc2626'],
    ['key' => 'no_award_technical', 'label' => 'No Award — Technical',       'color' => '#f0734f'],
    ['key' => 'cancelled',          'label' => 'Cancelled',                  'color' => '#54627a'],
    ['key' => 'not_submitted',      'label' => 'Not Submitted',              'color' => '#d97706'],
  ];

  /** Derived statuses that mean the bid was actually submitted. */
  const SUBMITTED_KEYS = ['submitted', 'submitted_ss', 'award', 'no_award_pricing', 'no_award_technical'];
  const WON_KEYS       = ['award'];
  const LOST_KEYS      = ['no_award_pricing', 'no_award_technical'];
  const PENDING_KEYS   = ['tbd', 'bid', 'submitted', 'submitted_ss'];

  /** Pricing-effort sub-buckets (of completed/priced bids). */
  const PRICING_BUCKETS = [
    ['key' => 'submitted',     'label' => 'Submitted',     'color' => '#4f6ef0'],
    ['key' => 'not_submitted', 'label' => 'Not Submitted', 'color' => '#d97706'],
    ['key' => 'no_bid',        'label' => 'No Bid',        'color' => '#7d8ba0'],
  ];

  /**
   * A quote's value = product total + services subtotal. The pipeline dollar figures
   * must match the Charts tab (RepositorioRfq::getAnnualAwardsDataByMonthForYears),
   * so every value query joins services and uses VALUE_EXPR — never rfq.total_price alone.
   */
  const SERVICES_JOIN = "
    LEFT JOIN (
      SELECT id_rfq, SUM(COALESCE(total_price, 0)) AS services_total
      FROM services
      GROUP BY id_rfq
    ) svc ON svc.id_rfq = rfq.id";
  const VALUE_EXPR = "(COALESCE(rfq.total_price, 0) + COALESCE(svc.services_total, 0))";

  /**
   * Builds the period WHERE fragment (without the leading AND) and its bind params.
   * Returns [sqlFragment, params]. For 'year' mode only the year is constrained.
   */
  private static function periodClause(array $period) {
    $parsed = "STR_TO_DATE(rfq.issue_date, '%m/%d/%Y')";
    $sql = "$parsed IS NOT NULL AND YEAR($parsed) = :year";
    $params = [':year' => (int)$period['year']];

    if (($period['mode'] ?? 'year') === 'quarter') {
      $sql .= " AND QUARTER($parsed) = :quarter";
      $params[':quarter'] = (int)$period['quarter'];
    } elseif (($period['mode'] ?? 'year') === 'month') {
      $sql .= " AND MONTH($parsed) = :month";
      $params[':month'] = (int)$period['month'];
    }
    return [$sql, $params];
  }

  /** Human-readable period label (e.g. "Q2 2026", "March 2026", "2026"). */
  public static function periodLabel(array $period) {
    $months = ['', 'January', 'February', 'March', 'April', 'May', 'June',
               'July', 'August', 'September', 'October', 'November', 'December'];
    $mode = $period['mode'] ?? 'year';
    if ($mode === 'quarter') return 'Q' . (int)$period['quarter'] . ' ' . (int)$period['year'];
    if ($mode === 'month')   return ($months[(int)$period['month']] ?? '') . ' ' . (int)$period['year'];
    return (string)(int)$period['year'];
  }

  /**
   * Full dashboard payload for a period: KPIs, status distribution, the two category
   * breakdowns, and pricing effort. Pure aggregation — safe to json_encode directly.
   */
  public static function getMetrics($conexion, array $period) {
    [$periodSql, $params] = self::periodClause($period);

    // --- status counts + summed value per bucket (drives KPIs + status donut) ---
    $sql = "SELECT bucket AS skey, COUNT(*) AS cnt, COALESCE(SUM(total_price), 0) AS val
            FROM (
              SELECT " . self::STATUS_CASE . " AS bucket, " . self::VALUE_EXPR . " AS total_price
              FROM rfq" . self::SERVICES_JOIN . "
              WHERE rfq.deleted = 0 AND $periodSql
            ) t
            GROUP BY bucket";
    $rows = self::run($conexion, $sql, $params);

    $counts = [];
    $values = [];
    foreach (self::STATUSES as $s) { $counts[$s['key']] = 0; $values[$s['key']] = 0.0; }
    foreach ($rows as $r) {
      $counts[$r['skey']] = (int)$r['cnt'];
      $values[$r['skey']] = (float)$r['val'];
    }

    $sumKeys = function(array $map, array $keys) {
      $t = 0; foreach ($keys as $k) { $t += $map[$k] ?? 0; } return $t;
    };

    $total          = array_sum($counts);
    $totalValue     = array_sum($values);
    $submittedCount = $sumKeys($counts, self::SUBMITTED_KEYS);
    $submittedValue = $sumKeys($values, self::SUBMITTED_KEYS);
    $awardedCount   = $sumKeys($counts, self::WON_KEYS);
    $awardedValue   = $sumKeys($values, self::WON_KEYS);
    $lostCount      = $sumKeys($counts, self::LOST_KEYS);
    $pendingCount   = $sumKeys($counts, self::PENDING_KEYS);

    // Win/Loss math (feature report #5): denominator = regular SUBMITTED + AWARD + lost.
    // Sources-sought is deliberately excluded; the three shares sum to 100%.
    $winPending     = $counts['submitted']; // regular submitted, still awaiting a decision
    $winDecided     = $awardedCount + $lostCount;
    $winDenominator = $winPending + $awardedCount + $lostCount;
    $winRate        = $winDenominator === 0 ? null : round($awardedCount / $winDenominator, 4);
    $winLoss = [
      'denominator' => $winDenominator,
      'decided'     => $winDecided,
      'awarded'     => $awardedCount,
      'noAward'     => $lostCount,
      'pending'     => $winPending,
      'winRate'     => $winRate,
      'series'      => [
        ['key' => 'won',     'label' => 'Awarded',  'color' => '#16a34a', 'count' => $awardedCount],
        ['key' => 'lost',    'label' => 'No Award', 'color' => '#dc2626', 'count' => $lostCount],
        ['key' => 'pending', 'label' => 'Pending',  'color' => '#d97706', 'count' => $winPending],
      ],
    ];

    // --- status distribution series (all 10 buckets, ordered) ---
    $statusSeries = [];
    foreach (self::STATUSES as $s) {
      $statusSeries[] = [
        'key'   => $s['key'],
        'label' => $s['label'],
        'color' => $s['color'],
        'count' => $counts[$s['key']],
        'value' => $values[$s['key']],
      ];
    }

    return [
      'period'    => ['mode' => $period['mode'] ?? 'year', 'year' => (int)$period['year'],
                      'quarter' => isset($period['quarter']) ? (int)$period['quarter'] : null,
                      'month' => isset($period['month']) ? (int)$period['month'] : null,
                      'label' => self::periodLabel($period)],
      'count'     => $total,
      'empty'     => $total === 0,
      'totalValue'     => $totalValue,
      'submittedCount' => $submittedCount,
      'submittedValue' => $submittedValue,
      'awardedCount'   => $awardedCount,
      'awardedValue'   => $awardedValue,
      'lostCount'      => $lostCount,
      'pendingCount'   => $pendingCount,
      'decided'        => $winDecided,
      'winRate'        => $winRate,
      'winLoss'        => $winLoss,
      'status'         => $statusSeries,
      'awardsByCategory'    => self::categoryBreakdown($conexion, $period, 'awards'),
      'submittedByCategory' => self::categoryBreakdown($conexion, $period, 'submitted'),
      'pricing'             => self::pricingEffort($conexion, $period),
    ];
  }

  /**
   * Counts per type_of_bid (category) for either won bids ('awards') or all submitted
   * bids ('submitted'). Returns only categories with count > 0, ordered desc.
   */
  public static function categoryBreakdown($conexion, array $period, $bucket) {
    [$periodSql, $params] = self::periodClause($period);
    $keys = $bucket === 'awards' ? self::WON_KEYS : self::SUBMITTED_KEYS;
    $inList = self::inClause($keys, 'b', $params);

    $sql = "SELECT category, COUNT(*) AS cnt
            FROM (
              SELECT rfq.type_of_bid AS category, " . self::STATUS_CASE . " AS bucket
              FROM rfq
              WHERE rfq.deleted = 0 AND $periodSql
            ) t
            WHERE bucket IN ($inList)
            GROUP BY category
            HAVING cnt > 0
            ORDER BY cnt DESC, category ASC";
    $rows = self::run($conexion, $sql, $params);

    return array_map(function ($r) {
      return ['category' => $r['category'] !== '' ? $r['category'] : 'Uncategorized', 'count' => (int)$r['cnt']];
    }, $rows);
  }

  /**
   * Pricing effort: of completed (priced) bids, how many landed in Submitted /
   * Not Submitted / No Bid. Total is the count of priced bids reached.
   */
  public static function pricingEffort($conexion, array $period) {
    [$periodSql, $params] = self::periodClause($period);
    $submittedIn = self::inClause(self::SUBMITTED_KEYS, 's', $params);

    $sql = "SELECT
              SUM(bucket IN ($submittedIn)) AS submitted_n,
              SUM(bucket = 'not_submitted') AS not_submitted_n,
              SUM(bucket = 'no_bid') AS no_bid_n,
              COUNT(*) AS total
            FROM (
              SELECT " . self::STATUS_CASE . " AS bucket
              FROM rfq
              WHERE rfq.deleted = 0 AND rfq.completado = 1 AND $periodSql
            ) t";
    $rows = self::run($conexion, $sql, $params);
    $r = $rows[0] ?? ['submitted_n' => 0, 'not_submitted_n' => 0, 'no_bid_n' => 0, 'total' => 0];

    $byKey = [
      'submitted'     => (int)$r['submitted_n'],
      'not_submitted' => (int)$r['not_submitted_n'],
      'no_bid'        => (int)$r['no_bid_n'],
    ];
    $buckets = [];
    foreach (self::PRICING_BUCKETS as $b) {
      $buckets[] = ['key' => $b['key'], 'label' => $b['label'], 'color' => $b['color'], 'count' => $byKey[$b['key']]];
    }
    return ['total' => (int)$r['total'], 'buckets' => $buckets];
  }

  /**
   * Drill-down list of quotes for a clicked chart segment.
   * $spec: ['type' => status|category|priced|winloss, ...]. Returns up to $limit rows.
   */
  public static function getDrillDown($conexion, array $period, array $spec, $limit = 300) {
    [$periodSql, $params] = self::periodClause($period);
    $where = self::drillCondition($spec, $params);
    if ($where === null) return [];

    $sql = "SELECT id, email_code, name, type_of_bid, total_price, issue_date, bucket
            FROM (
              SELECT rfq.id, rfq.email_code, rfq.name, rfq.type_of_bid,
                     " . self::VALUE_EXPR . " AS total_price, rfq.issue_date, rfq.completado,
                     " . self::STATUS_CASE . " AS bucket
              FROM rfq" . self::SERVICES_JOIN . "
              WHERE rfq.deleted = 0 AND $periodSql
            ) t
            WHERE $where
            ORDER BY STR_TO_DATE(issue_date, '%m/%d/%Y') DESC, id DESC
            LIMIT " . (int)$limit;
    $rows = self::run($conexion, $sql, $params);

    $labels = [];
    foreach (self::STATUSES as $s) { $labels[$s['key']] = $s['label']; }

    return array_map(function ($r) use ($labels) {
      $name = trim(preg_replace('/\s+/', ' ', (string)$r['name']));
      return [
        'id'       => (int)$r['id'],
        'code'     => $r['email_code'] !== '' ? $r['email_code'] : ('RFQ-' . $r['id']),
        'name'     => $name !== '' ? $name : ('Proposal #' . $r['id']),
        'category' => $r['type_of_bid'] !== '' ? $r['type_of_bid'] : 'Uncategorized',
        'value'    => (float)$r['total_price'],
        'date'     => $r['issue_date'],
        'status'   => $labels[$r['bucket']] ?? $r['bucket'],
      ];
    }, $rows);
  }

  /** Builds the drill-down WHERE condition over the derived `bucket` alias. */
  private static function drillCondition(array $spec, array &$params) {
    $type = $spec['type'] ?? '';
    $validKeys = array_column(self::STATUSES, 'key');

    if ($type === 'status') {
      $key = $spec['key'] ?? '';
      if (!in_array($key, $validKeys, true)) return null;
      $params[':dkey'] = $key;
      return "bucket = :dkey";
    }
    if ($type === 'winloss') {
      $k = $spec['key'] ?? '';
      $keys = $k === 'won' ? self::WON_KEYS : ($k === 'pending' ? ['submitted'] : self::LOST_KEYS);
      return "bucket IN (" . self::inClause($keys, 'w', $params) . ")";
    }
    if ($type === 'category') {
      $keys = ($spec['bucket'] ?? '') === 'awards' ? self::WON_KEYS : self::SUBMITTED_KEYS;
      $params[':dcat'] = $spec['category'] ?? '';
      return "type_of_bid = :dcat AND bucket IN (" . self::inClause($keys, 'c', $params) . ")";
    }
    if ($type === 'priced') {
      $key = $spec['key'] ?? '';
      if ($key === 'submitted') {
        return "completado = 1 AND bucket IN (" . self::inClause(self::SUBMITTED_KEYS, 'p', $params) . ")";
      }
      if ($key === 'not_submitted') return "completado = 1 AND bucket = 'not_submitted'";
      if ($key === 'no_bid')        return "completado = 1 AND bucket = 'no_bid'";
      return null;
    }
    return null;
  }

  /** Builds a parameterised IN (...) list, appending binds to $params; returns the placeholder CSV. */
  private static function inClause(array $values, $prefix, array &$params) {
    $ph = [];
    foreach (array_values($values) as $i => $v) {
      $name = ":{$prefix}{$i}";
      $ph[] = $name;
      $params[$name] = $v;
    }
    return implode(',', $ph);
  }

  /** Prepares + executes a query and returns all rows (assoc). */
  private static function run($conexion, $sql, array $params) {
    $stmt = $conexion->prepare($sql);
    foreach ($params as $name => $value) {
      $stmt->bindValue($name, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
