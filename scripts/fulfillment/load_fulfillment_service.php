<?php
Conexion::abrir_conexion();
try {
  $conexion = Conexion::obtener_conexion();
  $fulfillment_service = $id_fulfillment_service ? FulfillmentServiceRepository::get_one($conexion, $id_fulfillment_service) : null;
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
      <div class="form-group">
        <label for="provider">Provider:</label>
        <select name="provider" id="provider" class="custom-select">
          <?php foreach ($providers_list as $provider) : ?>
            <option <?= $provider->get_company_name() == $fulfillment_service?->get_provider() ? 'selected' : ''; ?>>
              <?= htmlspecialchars($provider->get_company_name(), ENT_QUOTES, 'UTF-8'); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <small class="form-text text-muted">Select the provider for this service.</small>
        <input type="hidden" name="provider_original" value="<?= htmlspecialchars($fulfillment_service?->get_provider() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
      </div>

      <div class="form-group">
        <label for="transaction_date">Transaction Date:</label>
        <input type="text" id="transaction_date" readonly class="form-control form-control-sm" name="transaction_date"
          value="<?= $fulfillment_service && !empty($fulfillment_service->getTransactionDate()) ? date("m/d/Y", strtotime($fulfillment_service->getTransactionDate())) : ''; ?>">
        <small class="form-text text-muted">The date of the transaction (read-only).</small>
      </div>

      <div class="form-group">
        <label for="quantity">Quantity:</label>
        <input type="number" step=".01" id="quantity" class="form-control form-control-sm" name="quantity" value="<?= $fulfillment_service ? $fulfillment_service->get_quantity() : 0; ?>">
        <small class="form-text text-muted">Enter the quantity for the service.</small>
        <input type="hidden" name="quantity_original" value="<?= $fulfillment_service?->get_quantity(); ?>">
      </div>

      <div class="form-group">
        <label for="unit_cost">Unit Cost:</label>
        <input type="number" step=".01" id="unit_cost" class="form-control form-control-sm" name="unit_cost" value="<?= $fulfillment_service ? $fulfillment_service->get_unit_cost() : 0; ?>">
        <small class="form-text text-muted">Enter the unit cost for the service.</small>
        <input type="hidden" name="unit_cost_original" value="<?= $fulfillment_service?->get_unit_cost(); ?>">
      </div>

      <div class="form-group">
        <label for="other_cost">Other Cost:</label>
        <input type="number" step=".01" id="other_cost" class="form-control form-control-sm" name="other_cost" value="<?= $fulfillment_service ? $fulfillment_service->get_other_cost() : 0; ?>">
        <small class="form-text text-muted">Include any additional costs related to this service.</small>
        <input type="hidden" name="other_cost_original" value="<?= $fulfillment_service?->get_other_cost(); ?>">
      </div>

      <div class="form-group">
        <label for="payment_term">Payment Term:</label>
        <select class="custom-select" name="payment_term" id="payment_term">
          <?php foreach ($payment_terms as $payment_term) : ?>
            <option <?= $payment_term->get_payment_term() == $fulfillment_service?->get_payment_term() ? 'selected' : ''; ?>>
              <?= htmlspecialchars($payment_term->get_payment_term(), ENT_QUOTES, 'UTF-8'); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <small class="form-text text-muted">Specify the payment terms for this service.</small>
        <input type="hidden" name="payment_term_original" value="<?= htmlspecialchars($fulfillment_service?->get_payment_term() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
      </div>

      <?php if ($_POST["isPartialInvoices"]) : ?>
        <div class="form-group">
          <label for="invoice">Invoice:</label>
          <select class="custom-select" name="invoice" id="invoice">
            <option value="">Choose an option</option>
            <?php foreach ($invoices as $invoice) : ?>
              <option value="<?= $invoice->get_id(); ?>" <?= $fulfillment_service?->getIdInvoice() == $invoice->get_id() ? 'selected' : ''; ?>>
                <?= htmlspecialchars($invoice->get_name(), ENT_QUOTES, 'UTF-8'); ?>
              </option>
            <?php endforeach; ?>
          </select>
          <small class="form-text text-muted">Select an invoice associated with this service, if applicable.</small>
        </div>
      <?php endif; ?>

      <div class="form-group">
        <label for="comment">Comment:</label>
        <textarea id="comment" name="comment" rows="5" class="form-control form-control-sm"><?= htmlspecialchars($fulfillment_service?->getComments() ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
        <small class="form-text text-muted">Add any additional comments related to this service.</small>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="id_fulfillment_service" name="id_fulfillment_service" value="<?= $fulfillment_service?->get_id(); ?>">
<input type="hidden" id="id_service" name="id_service" value="<?= $_POST["idService"] ?? $fulfillment_service?->get_id_service(); ?>">

<div class="modal-footer">
  <button type="submit" name="save_edit_fulfillment_service_button" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>