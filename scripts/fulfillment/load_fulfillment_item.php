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

<div class="modal-body user-form">

  <div class="form-group">
    <label>Provider</label>
    <input type="hidden" name="provider_original" value="<?= htmlspecialchars($fulfillment_item?->get_provider() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    <select name="provider" id="provider" class="form-control form-control-sm">
      <?php foreach ($providers_list as $provider) : ?>
        <option <?= $provider->get_company_name() === $fulfillment_item?->get_provider() ? 'selected' : ''; ?>>
          <?= htmlspecialchars($provider->get_company_name(), ENT_QUOTES, 'UTF-8'); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="form-group">
    <label>Transaction Date</label>
    <input type="text" id="transaction_date" class="form-control form-control-sm" name="transaction_date" readonly
      value="<?= $fulfillment_item && $fulfillment_item->getTransactionDate() ? date("m/d/Y", strtotime($fulfillment_item->getTransactionDate())) : ''; ?>">
  </div>

  <div class="form-row">
    <div class="col-md-6">
      <div class="form-group">
        <label>Quantity</label>
        <input type="hidden" name="quantity_original" value="<?= htmlspecialchars($fulfillment_item?->get_quantity() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <input type="number" step=".01" id="quantity" class="form-control form-control-sm" name="quantity" value="<?= $fulfillment_item?->get_quantity() ?? 0; ?>">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label>Unit Cost</label>
        <input type="hidden" name="unit_cost_original" value="<?= htmlspecialchars($fulfillment_item?->get_unit_cost() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <div class="input-group input-group">
          <div class="input-group-prepend"><span class="input-group-text">$</span></div>
          <input type="number" step=".01" id="unit_cost" class="form-control" name="unit_cost" value="<?= $fulfillment_item?->get_unit_cost() ?? 0; ?>">
        </div>
      </div>
    </div>
  </div>

  <div class="form-row">
    <div class="col-md-6">
      <div class="form-group">
        <label>Other Cost</label>
        <input type="hidden" name="other_cost_original" value="<?= htmlspecialchars($fulfillment_item?->get_other_cost() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <div class="input-group input-group">
          <div class="input-group-prepend"><span class="input-group-text">$</span></div>
          <input type="number" step=".01" id="other_cost" class="form-control" name="other_cost" value="<?= $fulfillment_item?->get_other_cost() ?? 0; ?>">
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label>Payment Term</label>
        <input type="hidden" name="payment_term_original" value="<?= htmlspecialchars($fulfillment_item?->get_payment_term() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <select name="payment_term" id="payment_term" class="form-control form-control-sm">
          <?php foreach ($payment_terms as $payment_term) : ?>
            <option <?= $payment_term->get_payment_term() === $fulfillment_item?->get_payment_term() ? 'selected' : ''; ?>>
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
      <select name="invoice" id="invoice" class="form-control form-control-sm">
        <option value="">Choose an option</option>
        <?php foreach ($invoices as $invoice) : ?>
          <option value="<?= htmlspecialchars($invoice->get_id(), ENT_QUOTES, 'UTF-8'); ?>" <?= $fulfillment_item?->getIdInvoice() === $invoice->get_id() ? 'selected' : ''; ?>>
            <?= htmlspecialchars($invoice->get_name(), ENT_QUOTES, 'UTF-8'); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  <?php endif; ?>

  <div class="form-group">
    <label>Comment</label>
    <textarea id="comment" name="comment" class="form-control form-control-sm" rows="4"><?= htmlspecialchars($fulfillment_item?->get_comments() ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
  </div>

</div>

<input type="hidden" name="id_fulfillment_item" value="<?= htmlspecialchars($fulfillment_item?->get_id() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="id_item" value="<?= htmlspecialchars($_POST["idItem"] ? $_POST["idItem"] : $fulfillment_item?->get_id_item(), ENT_QUOTES, 'UTF-8'); ?>">

<div class="modal-footer d-flex justify-content-end" style="gap:8px;">
  <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check mr-1"></i> Save</button>
  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-ban mr-1"></i> Cancel</button>
</div>
