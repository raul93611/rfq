<?php
switch ($quote->obtener_sales_commission()) {
  case 'No commission':
    $sales_commission = ['$0', '0', 'No commission'];
    break;
  case 'Same commission':
    $sales_commission = [
      '$' . number_format($quote->obtener_re_quote_rfq_profit(), 2) . ' / ' . number_format($quote->obtener_re_quote_rfq_profit_percentage(), 2) . '%',
      number_format(($quote->obtener_re_quote_rfq_profit()) * 0.03, 2),
      'Same commission'
    ];
    break;
  case 'Other commission':
    $sales_commission = [
      '$' . number_format($quote->getRfqFulfillmentProfit(), 2) . ' / ' . number_format($quote->getRfqFulfillmentProfitPercentage(), 2) . '%',
      number_format($quote->getRfqFulfillmentProfit() * 0.03, 2),
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
      <span class="text-success"><?php echo "Profit basis for commission ($sales_commission[2]): $sales_commission[0]"; ?>
        <button type="button" class="m-0 p-0 btn btn-link text-success" data-toggle="tooltip" data-html="true" title="<?php echo $quote->obtener_sales_commission_comment(); ?>">
          <i class="fas fa-comment"></i>
        </button>
      </span>
      <br>
      <span class="text-success"><?php echo "Real Sales Commission: $$sales_commission[1]"; ?></span>
    </div>
  </div>
</div>