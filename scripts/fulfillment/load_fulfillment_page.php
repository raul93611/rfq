<?php
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$items_exists = RepositorioItem::items_exists(Conexion::obtener_conexion(), $id_rfq);
$total_services = ServiceRepository::get_total(Conexion::obtener_conexion(), $id_rfq);
$invoices = InvoiceRepository::get_all_by_id_rfq(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
if ($quote->obtener_invoice()) include_once 'plantillas/fulfillment/templates/sales_commission.inc.php'; 
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
<?php if ($quote->obtener_fulfillment_pending()) : ?>
  <?php
  Conexion::abrir_conexion();
  $invoicesRetrieved = InvoiceRepository::listInvoices(Conexion::obtener_conexion(), $id_rfq);
  Conexion::cerrar_conexion();
  ?>
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
            <th>SALES COMMISSION</th>
            <th>OPTIONS</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($invoicesRetrieved as $key => $invoiceRetrieved) : ?>
            <tr>
              <td><?= $invoiceRetrieved['invoice_name'] ?></td>
              <td><?= $invoiceRetrieved['invoice_date'] ?></td>
              <td><?= $invoiceRetrieved['total_item_price'] ?></td>
              <td><?= $invoiceRetrieved['total_real_cost'] ?></td>
              <td><?= $invoiceRetrieved['total_profit'] ?></td>
              <td><b class="text-success"><?= $invoiceRetrieved['sales_commission'] ?></b></td>
              <td><button data-id="<?= $invoiceRetrieved['id_invoice'] ?>" class="attach-sales-commission-button btn btn-warning"><i class="fas fa-paperclip"></i></button></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>
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
<?php if ($quote->obtener_invoice()) : ?>
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title"><i class="fas fa-dollar-sign"></i> Total Profit - Real Sales Commission</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <h3 class="text-info text-center">Total Profit - Real Sales Commission: $ <?= number_format($quote->obtener_real_fulfillment_profit() - str_replace(',', '', $sales_commission[1]), 2); ?></h3>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>