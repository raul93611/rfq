<?php
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$items_exists = RepositorioItem::items_exists(Conexion::obtener_conexion(), $id_rfq);
$total_services = ServiceRepository::get_total(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
?>
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-highlighter"></i> RFQ</h3>
  </div>
  <div id="fulfillment_box" class="card-body">
    <?php if ($items_exists) :
      FulfillmentRepository::items_list($id_rfq);
    else : ?>
      <h3 class="text-info text-center"><i class="fas fa-exclamation-circle"></i> No Items to display!</h3>
    <?php endif; ?>
  </div>
</div>
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-highlighter"></i> RFP</h3>
  </div>
  <div id="fulfillment_services_box" class="card-body">
    <?php if ($quote->isServices()) :
      FulfillmentRepository::services_list($id_rfq);
    else : ?>
      <h3 class="text-info text-center"><i class="fas fa-exclamation-circle"></i> No Services to display!</h3>
    <?php endif; ?>
  </div>
</div>
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-dollar-sign"></i> Invoices</h3>
  </div>
  <div class="card-body">
  <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>INVOICE</th>
          <th>TOTAL PRICE</th>
          <th>REAL COST</th>
          <th>PROFIT</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
  </table>
  </div>
</div>
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-dollar-sign"></i> Total</h3>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-4">
        <h3 class="text-info text-center">Total Price: $ <?= number_format($quote->obtener_quote_total_price(), 2); ?></h3>
      </div>
      <div class="col-md-4">
        <h3 class="text-info text-center">Total profit: $ <?= number_format($quote->obtener_real_fulfillment_profit(), 2); ?></h3>
      </div>
      <div class="col-md-4">
        <h3 class="text-info text-center">Total profit(%): <?= number_format($quote->obtener_real_fulfillment_profit_percentage(), 2); ?></h3>
      </div>
    </div>
  </div>
</div>