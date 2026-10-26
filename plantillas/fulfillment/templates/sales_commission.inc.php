<?php
$saved_commission = $quote->obtener_saved_sales_commission();
if ($saved_commission !== null && $quote->obtener_invoice()):
  // Auto-calculated at invoice time and frozen (see SalesCommissionCalculator). Legacy quotes fall through to the manual card below.
  $tone = SalesCommissionCalculator::tone($saved_commission['reason']);
  $statusIcons = include 'plantillas/utilities/commission_status_icons.inc.php';
  $reason_label = SalesCommissionCalculator::reasonLabel($saved_commission['reason'], $saved_commission['fulfillment_pct'], $quote->obtener_type_of_contract());
  $saved_comment = trim((string)$quote->obtener_sales_commission_comment());
?>
  <div class="cs-strip" data-testid="cs-strip">
    <div class="cs-cell">
      <span class="cs-l">Type of Contract</span>
      <span class="cs-v" data-testid="cs-type"><?= htmlspecialchars($quote->obtener_type_of_contract() ?: '—', ENT_QUOTES, 'UTF-8') ?></span>
    </div>
    <div class="cs-cell">
      <span class="cs-l">Commission</span>
      <span class="cs-amt<?= $saved_commission['amount'] == 0 ? ' is-zero' : '' ?>" data-testid="cs-amount"><?= htmlspecialchars(SalesCommissionCalculator::money($saved_commission['amount']), ENT_QUOTES, 'UTF-8') ?></span>
    </div>
    <div class="cs-cell">
      <span class="cs-l">Reason</span>
      <span class="cs-chip <?= $tone ?>" data-testid="cs-reason">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $statusIcons[$tone] ?></svg>
        <?= htmlspecialchars($reason_label, ENT_QUOTES, 'UTF-8') ?>
      </span>
    </div>
    <div class="cs-cell">
      <span class="cs-l">Comment</span>
      <span class="cs-cmt<?= $saved_comment === '' ? ' is-empty' : '' ?>" data-testid="cs-comment"><?= htmlspecialchars($saved_comment === '' ? '—' : $saved_comment, ENT_QUOTES, 'UTF-8') ?></span>
    </div>
  </div>
<?php return; endif; ?>
<?php
// An auto quote whose invoice was removed has no commission until it is invoiced (and recalculated) again.
$commission_type    = $saved_commission !== null ? null : $quote->obtener_sales_commission();
$commission_comment = $quote->obtener_sales_commission_comment();

switch ($commission_type) {
  case 'No commission':
    $profit_basis      = '$0';
    $commission_amount = '$0.00';
    break;
  case 'Same commission':
    $profit            = $quote->obtener_re_quote_rfq_profit();
    $pct               = $quote->obtener_re_quote_rfq_profit_percentage();
    $profit_basis      = '$' . number_format($profit, 2) . ' / ' . number_format($pct, 2) . '%';
    $commission_amount = '$' . number_format($profit * 0.03, 2);
    break;
  case 'Other commission':
    $profit            = $quote->getRfqFulfillmentProfit();
    $pct               = $quote->getRfqFulfillmentProfitPercentage();
    $profit_basis      = '$' . number_format($profit, 2) . ' / ' . number_format($pct, 2) . '%';
    $commission_amount = '$' . number_format($profit * 0.03, 2);
    break;
  default:
    $commission_type = null;
    break;
}
?>

<?php if (!$commission_type): ?>

  <div class="section-empty-state mb-3">
    <i class="fas fa-percentage"></i>
    <p>No commission type set</p>
  </div>

<?php else: ?>

  <div class="d-flex align-items-center mb-3 px-2 py-3"
       style="background:#f8f9fa; border-radius:8px; gap:24px; flex-wrap:wrap;">

    <div>
      <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.4px; color:#8896a5;">Commission Type</div>
      <div style="font-size:13px; font-weight:600; color:#39485a;"><?= htmlspecialchars($commission_type, ENT_QUOTES, 'UTF-8') ?></div>
    </div>

    <div style="width:1px; height:32px; background:#dee2e6;"></div>

    <div>
      <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.4px; color:#8896a5;">Profit Basis</div>
      <div style="font-size:13px; font-weight:600; color:#39485a;"><?= htmlspecialchars($profit_basis, ENT_QUOTES, 'UTF-8') ?></div>
    </div>

    <div style="width:1px; height:32px; background:#dee2e6;"></div>

    <div>
      <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.4px; color:#8896a5;">Real Sales Commission</div>
      <div style="font-size:16px; font-weight:700; color:#13A8F0;"><?= htmlspecialchars($commission_amount, ENT_QUOTES, 'UTF-8') ?></div>
    </div>

    <?php if ($commission_comment): ?>
      <div style="width:1px; height:32px; background:#dee2e6;"></div>
      <div style="flex:1; min-width:0;">
        <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.4px; color:#8896a5;">Comment</div>
        <div style="font-size:13px; color:#39485a;"><?= htmlspecialchars($commission_comment, ENT_QUOTES, 'UTF-8') ?></div>
      </div>
    <?php endif; ?>

  </div>

<?php endif; ?>
