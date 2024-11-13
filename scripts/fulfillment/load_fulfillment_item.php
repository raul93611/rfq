<?php
Conexion::abrir_conexion();
try {
  $conexion = Conexion::obtener_conexion();
  $fulfillment_item = $_POST["idFulfillmentItem"] ? FulfillmentItemRepository::get_one($conexion, $_POST["idFulfillmentItem"]) : null;
  $providers_list = ProviderListRepository::get_all($conexion);
  $payment_terms = PaymentTermRepository::get_all($conexion);
  $invoices = InvoiceRepository::get_all_by_id_rfq($conexion, $_POST["idRfq"]);
} finally {
  Conexion::cerrar_conexion();
}
?>

<div class="modal-body">
  <div class="row">
    <div class="col-md-12">

      <!-- Provider Selection -->
      <div class="form-group">
        <label for="provider">Provider:</label>
        <input type="hidden" name="provider_original" value="<?= htmlspecialchars($fulfillment_item?->get_provider() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <select name="provider" id="provider" class="custom-select">
          <?php foreach ($providers_list as $provider) : ?>
            <option <?= $provider->get_company_name() === $fulfillment_item?->get_provider() ? 'selected' : ''; ?>>
              <?= htmlspecialchars($provider->get_company_name(), ENT_QUOTES, 'UTF-8'); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <small class="form-text text-muted">Select the provider responsible for this fulfillment item.</small>
      </div>

      <!-- Transaction Date -->
      <div class="form-group">
        <label for="transaction_date">Transaction Date:</label>
        <input type="text" id="transaction_date" class="form-control form-control-sm" name="transaction_date" readonly
          value="<?= $fulfillment_item && $fulfillment_item->getTransactionDate() ? date("m/d/Y", strtotime($fulfillment_item->getTransactionDate())) : ''; ?>">
        <small class="form-text text-muted">The date the transaction was recorded.</small>
      </div>

      <!-- Quantity -->
      <div class="form-group">
        <label for="quantity">Quantity:</label>
        <input type="hidden" name="quantity_original" value="<?= htmlspecialchars($fulfillment_item?->get_quantity() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <input type="number" step=".01" id="quantity" class="form-control form-control-sm" name="quantity" value="<?= $fulfillment_item?->get_quantity() ?? 0; ?>">
        <small class="form-text text-muted">Enter the quantity of items fulfilled.</small>
      </div>

      <!-- Unit Cost -->
      <div class="form-group">
        <label for="unit_cost">Unit Cost:</label>
        <input type="hidden" name="unit_cost_original" value="<?= htmlspecialchars($fulfillment_item?->get_unit_cost() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <input type="number" step=".01" id="unit_cost" class="form-control form-control-sm" name="unit_cost" value="<?= $fulfillment_item?->get_unit_cost() ?? 0; ?>">
        <small class="form-text text-muted">Specify the cost per unit for this item.</small>
      </div>

      <!-- Other Cost -->
      <div class="form-group">
        <label for="other_cost">Other Cost:</label>
        <input type="hidden" name="other_cost_original" value="<?= htmlspecialchars($fulfillment_item?->get_other_cost() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <input type="number" step=".01" id="other_cost" class="form-control form-control-sm" name="other_cost" value="<?= $fulfillment_item?->get_other_cost() ?? 0; ?>">
        <small class="form-text text-muted">Enter any additional costs associated with this item.</small>
      </div>

      <!-- Payment Term -->
      <div class="form-group">
        <label for="payment_term">Payment Term:</label>
        <input type="hidden" name="payment_term_original" value="<?= htmlspecialchars($fulfillment_item?->get_payment_term() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <select name="payment_term" id="payment_term" class="custom-select">
          <?php foreach ($payment_terms as $payment_term) : ?>
            <option <?= $payment_term->get_payment_term() === $fulfillment_item?->get_payment_term() ? 'selected' : ''; ?>>
              <?= htmlspecialchars($payment_term->get_payment_term(), ENT_QUOTES, 'UTF-8'); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <small class="form-text text-muted">Select the payment term for this item.</small>
      </div>

      <!-- Invoice (if applicable) -->
      <?php if ($_POST["isPartialInvoices"]) : ?>
        <div class="form-group">
          <label for="invoice">Invoice:</label>
          <select name="invoice" id="invoice" class="custom-select">
            <option value="">Choose an option</option>
            <?php foreach ($invoices as $invoice) : ?>
              <option value="<?= htmlspecialchars($invoice->get_id(), ENT_QUOTES, 'UTF-8'); ?>" <?= $fulfillment_item?->getIdInvoice() === $invoice->get_id() ? 'selected' : ''; ?>>
                <?= htmlspecialchars($invoice->get_name(), ENT_QUOTES, 'UTF-8'); ?>
              </option>
            <?php endforeach; ?>
          </select>
          <small class="form-text text-muted">Choose an invoice if applicable.</small>
        </div>
      <?php endif; ?>

      <!-- Comment -->
      <div class="form-group">
        <label for="comment">Comment:</label>
        <textarea id="comment" name="comment" class="form-control form-control-sm" rows="5"><?= htmlspecialchars($fulfillment_item?->get_comments() ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
        <small class="form-text text-muted">Add any relevant comments or notes for this item.</small>
      </div>

    </div>
  </div>
</div>

<!-- Hidden Fields -->
<input type="hidden" name="id_fulfillment_item" value="<?= htmlspecialchars($fulfillment_item?->get_id() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="id_item" value="<?= htmlspecialchars($_POST["idItem"] ? $_POST["idItem"] : $fulfillment_item?->get_id_item(), ENT_QUOTES, 'UTF-8'); ?>">

<div class="modal-footer">
  <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>