<input type="hidden" name="id_rfq" value="<?= $quote->obtener_id(); ?>">
<?php
RepositorioItem::escribir_items($quote->obtener_id());
if ($quote->isServices()) {
  include_once 'plantillas/services/services.inc.php';
}
?>
<div class="row mt-4">
  <div class="col-md-4">
    <h3 class="text-info text-center">Total Price: $ <?= number_format($quote->obtener_quote_total_price(), 2); ?></h3>
  </div>
  <div class="col-md-4">
    <h3 class="text-info text-center">Total profit: $ <?= number_format($quote->obtener_quote_profit(), 2); ?></h3>
  </div>
  <div class="col-md-4">
    <h3 class="text-info text-center">Total profit(%): <?= number_format($quote->obtener_quote_profit_percentage(), 2); ?></h3>
  </div>
</div>