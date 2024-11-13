<?php
$sales_commission = [];
switch ($quote->obtener_sales_commission()) {
  case 'No commission':
    $sales_commission = ['$0', '0', 'No commission'];
    break;
  case 'Same commission':
    $profit = $quote->obtener_re_quote_rfq_profit();
    $profit_percentage = $quote->obtener_re_quote_rfq_profit_percentage();
    $commission_amount = number_format($profit * 0.03, 2);
    $sales_commission = [
      '$' . number_format($profit, 2) . ' / ' . number_format($profit_percentage, 2) . '%',
      $commission_amount,
      'Same commission'
    ];
    break;
  case 'Other commission':
    $fulfillment_profit = $quote->getRfqFulfillmentProfit();
    $fulfillment_percentage = $quote->getRfqFulfillmentProfitPercentage();
    $commission_amount = number_format($fulfillment_profit * 0.03, 2);
    $sales_commission = [
      '$' . number_format($fulfillment_profit, 2) . ' / ' . number_format($fulfillment_percentage, 2) . '%',
      $commission_amount,
      'Other commission'
    ];
    break;
  default:
    $sales_commission = ['Not set', 'Not set', 'Not set'];
    break;
}
?>

<div class="container-fluid sales-commission">
  <div class="row">
    <div class="col-md-12">
      <span class="text-success">
        <?= "Profit basis for commission ({$sales_commission[2]}): {$sales_commission[0]}"; ?>
        <button type="button" class="m-0 p-0 btn btn-link text-success"
          data-toggle="tooltip" data-html="true"
          title="<?= htmlspecialchars($quote->obtener_sales_commission_comment() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <i class="fas fa-comment"></i>
        </button>
      </span>
      <br>
      <span class="text-success">
        <?= "Real Sales Commission: $" . htmlspecialchars($sales_commission[1], ENT_QUOTES, 'UTF-8'); ?>
      </span>
    </div>
  </div>
</div>