<div id="fulfillment_page" class="col-md-12">
  <!-- Include Sales Commission if invoice exists -->
  <?php include 'plantillas/fulfillment/templates/sales_commission.inc.php'; ?>

  <!-- RFQ Card -->
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title"><i class="fas fa-highlighter"></i> RFQ</h3>
    </div>
    <div id="fulfillment_box" class="card-body">
      <?php if ($items_exists) : ?>
        <?= FulfillmentRepository::items_list($id_rfq); ?>
      <?php else : ?>
        <h3 class="text-info text-center"><i class="fas fa-exclamation-circle"></i> No Items to display!</h3>
      <?php endif; ?>
    </div>
  </div>

  <!-- RFP Card -->
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title"><i class="fas fa-highlighter"></i> RFP</h3>
    </div>
    <div id="fulfillment_services_box" class="card-body">
      <?php if ($quote->isServices()) : ?>
        <?= FulfillmentRepository::services_list($id_rfq); ?>
      <?php else : ?>
        <h3 class="text-info text-center"><i class="fas fa-exclamation-circle"></i> No Services to display!</h3>
      <?php endif; ?>
    </div>
  </div>

  <!-- Invoices Card (if pending fulfillment) -->
  <?php if ($quote->obtener_fulfillment_pending()) : ?>
    <?php
    Conexion::abrir_conexion();
    $invoicesRetrieved = InvoiceRepository::listInvoices(Conexion::obtener_conexion(), $id_rfq);
    $isSalesCommissionAttached = InvoiceRepository::isSalesCommissionAttached(Conexion::obtener_conexion(), $id_rfq);
    Conexion::cerrar_conexion();
    ?>
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-dollar-sign"></i> Invoices</h3>
      </div>
      <div class="card-body">
        <?php if (!$isSalesCommissionAttached) : ?>
          <div class="mb-3 text-danger font-weight-bold">Sales Commission is not attached!</div>
        <?php endif; ?>
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>INVOICE</th>
              <th>INVOICE DATE</th>
              <th>TOTAL PRICE</th>
              <th>REAL COST</th>
              <th>PROFIT</th>
              <th>SALES COMMISSION</th>
              <th>OPTIONS</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($invoicesRetrieved as $invoice) : ?>
              <tr>
                <td><?= htmlspecialchars($invoice['invoice_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?= htmlspecialchars($invoice['invoice_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?= number_format($invoice['total_item_price'], 2); ?></td>
                <td><?= number_format($invoice['total_real_cost'], 2); ?></td>
                <td><?= number_format($invoice['total_profit'] - (float)str_replace(',', '', $sales_commission[1] ?? 0), 2); ?></td>
                <td><b class="text-success"><?= htmlspecialchars($invoice['sales_commission'] ?? '', ENT_QUOTES, 'UTF-8'); ?></b></td>
                <td><button data-id="<?= $invoice['id_invoice']; ?>" class="attach-sales-commission-button btn btn-warning"><i class="fas fa-paperclip"></i></button></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>

  <div class="px-2 py-2 card-footer footer_totals d-inline-flex">
    <div class="d-flex flex-nowrap align-items-center">
      <div class="px-4">
        <h5 class="mb-0">
          <small class="text-info">Total Price:</small><br>
          <span>$<?= number_format($quote->obtener_quote_total_price(), 2); ?></span>
        </h5>
      </div>
      <div class="px-4 border-left">
        <h5 class="mb-0">
          <small class="text-info">Total Profit:</small><br>
          <span>$<?= number_format($quote->obtener_real_fulfillment_profit(), 2); ?></span>
        </h5>
      </div>
      <div class="px-4 border-left">
        <h5 class="mb-0">
          <small class="text-info">Profit (%):</small><br>
          <span><?= number_format($quote->obtener_real_fulfillment_profit_percentage(), 2); ?>%</span>
        </h5>
      </div>
    </div>
  </div>

  <!-- Total Profit - Real Sales Commission Card (if invoice exists) -->
  <?php if ($quote->obtener_invoice()) : ?>
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-dollar-sign"></i> Total Profit - Real Sales Commission</h3>
      </div>
      <div class="card-body text-center">
        <h3 class="text-info">Total Profit - Real Sales Commission: $ <?= number_format($quote->obtener_real_fulfillment_profit() - (float)str_replace(',', '', $sales_commission[1] ?? 0), 2); ?></h3>
      </div>
    </div>
  <?php endif; ?>
</div>