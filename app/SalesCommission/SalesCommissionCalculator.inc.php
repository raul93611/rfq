<?php

/**
 * Rule-based sales commission, calculated on the server when a quote is invoiced and frozen on
 * the quote (rfq.sales_commission_*). All profits are equipment only (services excluded).
 *
 * Rules, in order:
 *   1. Type of Contract is Moving and Logistics / Professional Services → $0 (not eligible).
 *   2. Quote price is $0 → no commission.
 *   3. Fulfillment profit % (rounded to 2 decimals) is under 6.00 → no commission.
 *   4. Basis = the smaller of re-quote profit and fulfillment profit (fulfillment alone if there
 *      is no re-quote; a tie goes to fulfillment).
 *   5. Commission = ROUND(max(basis, 0) × 3%, 2).
 */
class SalesCommissionCalculator {
  const RATE = 0.03;
  const MIN_FULFILLMENT_PCT = 6.00;
  const INELIGIBLE_TYPES = ['Moving and Logistics', 'Professional Services'];

  /**
   * @param float|int|string $price           rfq.total_price (equipment)
   * @param float|int|string $fulfillmentCost rfq.total_fulfillment (equipment)
   * @param float|int|string|null $requoteCost re-quote total_cost, null when there is no re-quote
   * @return array amount, reason, sales_commission (legacy string), basis ('requote'|'fulfillment'|null),
   *               basis_profit, fulfillment_profit/_pct, requote_profit/_pct, type_of_contract
   */
  public static function calculate($typeOfContract, $price, $fulfillmentCost, $requoteCost = null): array {
    $price = (float)$price;
    $type = trim((string)$typeOfContract);
    $fulfillmentProfit = round($price - (float)$fulfillmentCost, 2);
    $requoteProfit = $requoteCost === null ? null : round($price - (float)$requoteCost, 2);
    $pct = fn($profit) => $price > 0 ? round(100 * $profit / $price, 2) : null;

    $result = [
      'type_of_contract' => $type,
      'amount' => 0.0,
      'reason' => null,
      'sales_commission' => 'No commission',
      'basis' => null,
      'basis_profit' => null,
      'fulfillment_profit' => $fulfillmentProfit,
      'fulfillment_pct' => $pct($fulfillmentProfit),
      'requote_profit' => $requoteProfit,
      'requote_pct' => $requoteProfit === null ? null : $pct($requoteProfit),
    ];

    if (in_array($type, self::INELIGIBLE_TYPES, true)) {
      $result['reason'] = 'not_eligible_type';
    } elseif ($price <= 0) {
      $result['reason'] = 'no_price';
    } elseif ($result['fulfillment_pct'] < self::MIN_FULFILLMENT_PCT) {
      $result['reason'] = 'under_threshold';
    } else {
      $useRequote = $requoteProfit !== null && $requoteProfit < $fulfillmentProfit;
      $result['basis'] = $useRequote ? 'requote' : 'fulfillment';
      $result['basis_profit'] = $useRequote ? $requoteProfit : $fulfillmentProfit;
      if ($result['basis_profit'] <= 0) {
        $result['reason'] = 'no_profit';
      } else {
        $result['reason'] = $useRequote ? 'requote_smaller' : 'fulfillment_smaller';
        $result['amount'] = round($result['basis_profit'] * self::RATE, 2);
        $result['sales_commission'] = $useRequote ? 'Same commission' : 'Other commission';
      }
    }
    return $result;
  }

  /** Calculate from the quote's current numbers (never from what the client posted). */
  public static function forQuote($connection, $idRfq): array {
    $s = $connection->prepare('SELECT total_price, total_fulfillment, type_of_contract FROM rfq WHERE id = :id');
    $s->execute([':id' => $idRfq]);
    $rfq = $s->fetch(PDO::FETCH_ASSOC) ?: ['total_price' => 0, 'total_fulfillment' => 0, 'type_of_contract' => null];

    $s = $connection->prepare('SELECT total_cost FROM re_quotes WHERE id_rfq = :id LIMIT 1');
    $s->execute([':id' => $idRfq]);
    $requote = $s->fetch(PDO::FETCH_ASSOC);

    return self::calculate($rfq['type_of_contract'], $rfq['total_price'], $rfq['total_fulfillment'], $requote ? ($requote['total_cost'] ?? 0) : null);
  }

  /**
   * SQL for a quote's commission amount: the frozen amount for auto-calculated quotes,
   * the old manual-choice CASE for legacy ones (sales_commission_amount IS NULL).
   * Shared by the Sales Commission report, its Excel export and the monthly projection.
   */
  public static function amountSql(string $rfq = 'r', string $requote = 'rq'): string {
    $rate = self::RATE;
    return "COALESCE({$rfq}.sales_commission_amount, CASE {$rfq}.sales_commission"
      . " WHEN 'Other commission' THEN ROUND((COALESCE({$rfq}.total_price, 0) - COALESCE({$rfq}.total_fulfillment, 0)) * {$rate}, 2)"
      . " WHEN 'Same commission' THEN ROUND((COALESCE({$rfq}.total_price, 0) - COALESCE({$requote}.total_cost, 0)) * {$rate}, 2)"
      . " WHEN 'No commission' THEN 0 END)";
  }

  /** Plain-text reason for a result or a saved quote's reason code ($type only used by not_eligible_type). */
  public static function reasonLabel(?string $reason, $pct = null, $type = ''): string {
    switch ($reason) {
      case 'not_eligible_type': return 'Not eligible — ' . $type;
      case 'no_price': return 'No commission — quote price is $0';
      case 'under_threshold': return 'Under ' . (int)self::MIN_FULFILLMENT_PCT . '% (' . self::pct($pct) . ')';
      case 'requote_smaller': return 'Re-quote profit was smaller';
      case 'fulfillment_smaller': return 'Fulfillment profit was smaller';
      case 'no_profit': return 'Zero or negative profit';
      default: return '';
    }
  }

  /** Visual tone of a reason: sky (commission applies), amber, dark (not eligible) or slate (nothing to pay). */
  public static function tone(?string $reason): string {
    return ['requote_smaller' => 'sky', 'fulfillment_smaller' => 'sky', 'under_threshold' => 'amber', 'not_eligible_type' => 'dark'][$reason] ?? 'slate';
  }

  public static function money($n): string {
    $n = round((float)$n, 2);
    return ($n < 0 ? '−' : '') . '$' . number_format(abs($n), 2);
  }

  public static function pct($n): string {
    $n = round((float)$n, 2);
    return ($n < 0 ? '−' : '') . number_format(abs($n), 2) . '%';
  }
}
