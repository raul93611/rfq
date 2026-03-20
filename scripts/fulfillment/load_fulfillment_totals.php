<?php
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();

$commission_float = 0;
switch ($quote->obtener_sales_commission()) {
  case 'Same commission':
    $commission_float = $quote->obtener_re_quote_rfq_profit() * 0.03;
    break;
  case 'Other commission':
    $commission_float = $quote->getRfqFulfillmentProfit() * 0.03;
    break;
}
?>
<div class="quote-action-total">
  <span class="quote-action-total__label">Total Price</span>
  <span class="quote-action-total__value">$<?= number_format($quote->obtener_quote_total_price(), 2); ?></span>
</div>
<div class="quote-action-total">
  <span class="quote-action-total__label">Total Profit</span>
  <span class="quote-action-total__value">$<?= number_format($quote->obtener_real_fulfillment_profit(), 2); ?></span>
</div>
<div class="quote-action-total">
  <span class="quote-action-total__label">Profit %</span>
  <span class="quote-action-total__value"><?= number_format($quote->obtener_real_fulfillment_profit_percentage(), 2); ?>%</span>
</div>
<?php if ($quote->obtener_invoice()) : ?>
  <div class="quote-action-total">
    <span class="quote-action-total__label">Profit — Real SC</span>
    <span class="quote-action-total__value" style="color:#13A8F0;">$<?= number_format($quote->obtener_real_fulfillment_profit() - $commission_float, 2); ?></span>
  </div>
<?php endif; ?>
