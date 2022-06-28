<?php
Conexion::abrir_conexion();
$re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
switch ($quote->obtener_sales_commission()) {
  case 'No commission':
    $sales_commission = ['$0', '$0'];
    break;
  case 'Same commission':
    $sales_commission = [
      '$' . $quote->obtener_quote_total_price() - $re_quote->get_total_cost() . ' / ' . number_format(100 * (($quote->obtener_quote_total_price() - $re_quote->get_total_cost()) / $quote->obtener_quote_total_price()), 2) . '%',
      '$' . number_format(($quote->obtener_quote_total_price() - $re_quote->get_total_cost()) * 0.03, 2)
    ];
    break;
  case 'Other commission':
    $sales_commission = [
      '$' . number_format($quote->obtener_real_fulfillment_profit(), 2) . ' / ' . number_format($quote->obtener_real_fulfillment_profit_percentage(), 2) . '%',
      '$' . number_format($quote->obtener_real_fulfillment_profit() * 0.03, 2)
    ];
    break;
  default:
    $sales_commission = ['Not set', 'Not set'];
    break;
}
?>
<div class="container-fluid sales-commission">
  <div class="row">
    <div class="col-md-6">
      <h5 class="text-success"><?php echo 'Profit basis for commission: ' . $sales_commission[0]; ?>
        <button type="button" class="btn btn-link text-success" data-toggle="tooltip" data-html="true" title="<?php echo $quote-> obtener_sales_commission_comment(); ?>">
          <i class="fas fa-comment"></i>
        </button>
      </h5>
      <h5 class="text-success"><?php echo 'Real Sales Commission: ' . $sales_commission[1]; ?></h5>
    </div>
  </div>
</div>