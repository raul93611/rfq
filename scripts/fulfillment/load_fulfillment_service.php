<?php
Conexion::abrir_conexion();
try {
  $conexion = Conexion::obtener_conexion();
  $fulfillment_service = $_POST["idFulfillmentService"] ? FulfillmentServiceRepository::get_one($conexion, $_POST["idFulfillmentService"]) : null;
  $providers_list = ProviderListRepository::get_all($conexion);
  $payment_terms = PaymentTermRepository::get_all($conexion);
  $invoices = InvoiceRepository::get_all_by_id_rfq($conexion, $_POST["idRfq"]);
} finally {
  Conexion::cerrar_conexion();
}
?>

<div class="modal-body user-form">

  <div class="form-group">
    <label>Provider</label>
    <input type="hidden" name="provider_original" value="<?= htmlspecialchars($fulfillment_service?->get_provider() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    <select name="provider" id="provider" class="form-control form-control-sm">
      <?php foreach ($providers_list as $provider) : ?>
        <option <?= $provider->get_company_name() == $fulfillment_service?->get_provider() ? 'selected' : ''; ?>>
          <?= htmlspecialchars($provider->get_company_name(), ENT_QUOTES, 'UTF-8'); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="form-group">
    <label>Transaction Date</label>
    <input type="text" id="transaction_date" readonly class="form-control form-control-sm" name="transaction_date"
      value="<?= $fulfillment_service && !empty($fulfillment_service->getTransactionDate()) ? date("m/d/Y", strtotime($fulfillment_service->getTransactionDate())) : ''; ?>">
  </div>

  <div class="form-row">
    <div class="col-md-6">
      <div class="form-group">
        <label>Quantity</label>
        <input type="hidden" name="quantity_original" value="<?= $fulfillment_service?->get_quantity(); ?>">
        <input type="number" step=".01" id="quantity" class="form-control form-control-sm" name="quantity" value="<?= $fulfillment_service ? $fulfillment_service->get_quantity() : 0; ?>">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label>Unit Cost</label>
        <input type="hidden" name="unit_cost_original" value="<?= $fulfillment_service?->get_unit_cost(); ?>">
        <div class="input-group input-group">
          <div class="input-group-prepend"><span class="input-group-text">$</span></div>
          <input type="number" step=".01" id="unit_cost" class="form-control" name="unit_cost" value="<?= $fulfillment_service ? $fulfillment_service->get_unit_cost() : 0; ?>">
        </div>
      </div>
    </div>
  </div>

  <div class="form-row">
    <div class="col-md-6">
      <div class="form-group">
        <label>Other Cost</label>
        <input type="hidden" name="other_cost_original" value="<?= $fulfillment_service?->get_other_cost(); ?>">
        <div class="input-group input-group">
          <div class="input-group-prepend"><span class="input-group-text">$</span></div>
          <input type="number" step=".01" id="other_cost" class="form-control" name="other_cost" value="<?= $fulfillment_service ? $fulfillment_service->get_other_cost() : 0; ?>">
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label>Payment Term</label>
        <input type="hidden" name="payment_term_original" value="<?= htmlspecialchars($fulfillment_service?->get_payment_term() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <select class="form-control form-control-sm" name="payment_term" id="payment_term">
          <?php foreach ($payment_terms as $payment_term) : ?>
            <option <?= $payment_term->get_payment_term() == $fulfillment_service?->get_payment_term() ? 'selected' : ''; ?>>
              <?= htmlspecialchars($payment_term->get_payment_term(), ENT_QUOTES, 'UTF-8'); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>

  <?php if ($_POST["isPartialInvoices"]) : ?>
    <div class="form-group">
      <label>Invoice</label>
      <select class="form-control form-control-sm" name="invoice" id="invoice">
        <option value="">Choose an option</option>
        <?php foreach ($invoices as $invoice) : ?>
          <option value="<?= $invoice->get_id(); ?>" <?= $fulfillment_service?->getIdInvoice() == $invoice->get_id() ? 'selected' : ''; ?>>
            <?= htmlspecialchars($invoice->get_name(), ENT_QUOTES, 'UTF-8'); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  <?php endif; ?>

  <div class="form-group">
    <label>Comment</label>
    <textarea id="comment" name="comment" rows="4" class="form-control form-control-sm"><?= htmlspecialchars($fulfillment_service?->getComments() ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
  </div>

</div>

<input type="hidden" id="id_fulfillment_service" name="id_fulfillment_service" value="<?= $fulfillment_service?->get_id(); ?>">
<input type="hidden" id="id_service" name="id_service" value="<?= $_POST["idService"] ? $_POST["idService"] : $fulfillment_service?->get_id_service(); ?>">

<div class="modal-footer d-flex justify-content-end" style="gap:8px;">
  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-ban mr-1"></i> Cancel</button>
  <button type="submit" name="save_edit_fulfillment_service_button" class="btn btn-primary btn-sm"><i class="fa fa-check mr-1"></i> Save</button>
</div>
