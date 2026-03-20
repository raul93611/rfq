<div id="fulfillment_page" class="col-md-12">
  <!-- Include Sales Commission if invoice exists -->
  <?php include 'plantillas/fulfillment/templates/sales_commission.inc.php'; ?>

  <!-- RFQ Section -->
  <div class="quote-section-header">
    <div class="quote-section-header-title"><i class="fas fa-boxes mr-1"></i> RFQ</div>
  </div>
  <div id="fulfillment_box">
    <?php if ($items_exists) : ?>
      <?= FulfillmentRepository::items_list($id_rfq); ?>
    <?php else : ?>
      <div class="section-empty-state">
        <i class="fas fa-box-open"></i>
        <p>No items to display</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- RFP Section -->
  <div class="quote-section-header">
    <div class="quote-section-header-title"><i class="fas fa-concierge-bell mr-1"></i> RFP</div>
  </div>
  <div id="fulfillment_services_box">
    <?php if ($quote->isServices()) : ?>
      <?= FulfillmentRepository::services_list($id_rfq); ?>
    <?php else : ?>
      <div class="section-empty-state">
        <i class="fas fa-clipboard-list"></i>
        <p>No services to display</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- Invoices Card (if pending fulfillment) -->
  <?php if ($quote->obtener_fulfillment_pending()) : ?>
    <?php
    Conexion::abrir_conexion();
    $invoicesRetrieved = InvoiceRepository::listInvoices(Conexion::obtener_conexion(), $id_rfq);
    $isSalesCommissionAttached = InvoiceRepository::isSalesCommissionAttached(Conexion::obtener_conexion(), $id_rfq);
    Conexion::cerrar_conexion();

    // Calculate totals
    $totalInvoicePrice = 0;
    $totalRealCost = 0;
    $totalProfit = 0;

    foreach ($invoicesRetrieved as $invoice) {
      $totalInvoicePrice += (float)$invoice['total_item_price'];
      $totalRealCost += (float)$invoice['total_real_cost'];
      $totalProfit += (float)$invoice['total_profit'];
    }
    $totalProfit = $totalProfit - (float)str_replace(',', '', $sales_commission[1] ?? 0);
    ?>
    <div class="quote-section-header">
      <div class="quote-section-header-title"><i class="fas fa-file-invoice-dollar mr-1"></i> Invoices</div>
    </div>
    <div id="fulfillment_invoices_box">
      <?php if (!$isSalesCommissionAttached) : ?>
        <div class="mb-3" style="color:#e74c3c; font-weight:600; font-size:13px;">
          <i class="fas fa-exclamation-triangle mr-1"></i> Sales Commission is not attached!
        </div>
      <?php endif; ?>
      <div id="fulfillment_invoices_table_container">
        <table class="table table-hover">
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
                <td><?= $invoice['sales_commission'] == 'Attached' ? number_format($invoice['total_profit'] - (float)str_replace(',', '', $sales_commission[1] ?? 0), 2) : number_format($invoice['total_profit'], 2); ?></td>
                <td><b class="text-success"><?= htmlspecialchars($invoice['sales_commission'] ?? '', ENT_QUOTES, 'UTF-8'); ?></b></td>
                <td><button data-id="<?= $invoice['id_invoice']; ?>" class="attach-sales-commission-button btn btn-warning btn-sm"><i class="fas fa-paperclip"></i></button></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="2">TOTAL</th>
              <th id="fulfillment_invoice_total_price"><?= number_format($totalInvoicePrice, 2); ?></th>
              <th id="fulfillment_invoice_real_cost"><?= number_format($totalRealCost, 2); ?></th>
              <th id="fulfillment_invoice_profit"><?= number_format($totalProfit, 2); ?></th>
              <th></th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  <?php endif; ?>

</div>