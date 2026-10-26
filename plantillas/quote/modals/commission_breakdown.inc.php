<?php
/**
 * Read-only commission breakdown card for the Invoice modal. Rendered by
 * scripts/quote/commission_preview.php; $commission is a SalesCommissionCalculator result.
 * The number the server saves is recalculated at Save, this is only the preview.
 */
$tone = SalesCommissionCalculator::tone($commission['reason']);
$kind = ['sky' => 'ok', 'amber' => 'under6', 'dark' => 'ineligible', 'slate' => 'noprofit'][$tone];
$statusIcons = include 'plantillas/utilities/commission_status_icons.inc.php';

$status = $kind === 'ok'
  ? 'Eligible'
  : SalesCommissionCalculator::reasonLabel($commission['reason'], $commission['fulfillment_pct'], $commission['type_of_contract']);

$rows = [
  ['Fulfillment', 'fulfillment', $commission['fulfillment_profit'], $commission['fulfillment_pct']],
  ['Re-quote', 'requote', $commission['requote_profit'], $commission['requote_pct']],
];
$esc = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
?>
<div class="ic-card ic-<?= $kind ?>" data-testid="ic-card" data-reason="<?= $esc($commission['reason']) ?>">
  <div class="ic-head">
    <span class="ic-title">Sales Commission</span>
    <span class="ic-ro">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
      Calculated · read-only
    </span>
  </div>
  <div class="ic-kv">
    <span class="ic-k">Type of Contract</span>
    <span class="ic-v" data-testid="ic-type"><?= $esc($commission['type_of_contract'] ?: '—') ?></span>
  </div>
  <div class="ic-profit">
    <div class="ic-pgrid is-head"><span></span><span class="n">Profit $</span><span class="n">Profit %</span></div>
    <?php foreach ($rows as [$label, $key, $profit, $pct]): ?>
      <?php $used = $kind === 'ok' && $commission['basis'] === $key; ?>
      <div class="ic-pgrid<?= $used ? ' is-used' : '' ?>" data-testid="ic-row-<?= $key ?>">
        <span class="ic-pl"><?= $label ?><?php if ($used): ?><span class="ic-used" data-testid="ic-used">Used</span><?php endif; ?></span>
        <?php if ($profit === null): ?>
          <span class="n ic-dash">—</span><span class="n ic-dash">—</span>
        <?php else: ?>
          <span class="n<?= $profit < 0 ? ' is-neg' : '' ?>"><?= $esc(SalesCommissionCalculator::money($profit)) ?></span>
          <span class="n"><span class="<?= $kind === 'under6' && $key === 'fulfillment' ? 'ic-pct-low' : ($pct !== null && $pct < 0 ? 'is-neg' : '') ?>"><?= $pct === null ? '—' : $esc(SalesCommissionCalculator::pct($pct)) ?></span></span>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="ic-amount">
    <div>
      <div class="ic-amt-l">Commission</div>
      <div class="ic-amt" data-testid="ic-amount"><?= $esc(SalesCommissionCalculator::money($commission['amount'])) ?></div>
    </div>
    <span class="ic-rule"><b><?= (int)(SalesCommissionCalculator::RATE * 100) ?>%</b> of the smaller profit</span>
  </div>
  <div class="ic-status">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $statusIcons[$tone] ?></svg>
    <span data-testid="ic-status"><?= $esc($status) ?></span>
  </div>
</div>
