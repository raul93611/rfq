<?php
Conexion::abrir_conexion();
$fulfillment_item = is_null($id_fulfillment_item) ? null : FulfillmentItemRepository::get_one(Conexion::obtener_conexion(), $id_fulfillment_item);
$providers_list = ProviderListRepository::get_all(Conexion::obtener_conexion());
$payment_terms = PaymentTermRepository::get_all(Conexion::obtener_conexion());
$invoices = InvoiceRepository::get_all_by_id_rfq(Conexion::obtener_conexion(), $_POST["idRfq"]);
Conexion::cerrar_conexion();
?>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label>Provider:</label>
        <input type="hidden" name="provider_original" value="<?= $fulfillment_item?->get_provider(); ?>">
        <select name="provider" class="custom-select">
          <?php foreach ($providers_list as $key => $provider) : ?>
            <option <?= $provider->get_company_name() == $fulfillment_item?->get_provider() ? 'selected' : ''; ?>><?= $provider->get_company_name(); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="quantity">Quantity:</label>
        <input type="hidden" name="quantity_original" value="<?= $fulfillment_item?->get_quantity(); ?>">
        <input type="number" id="quantity" class="form-control form-control-sm" name="quantity" value="<?= $fulfillment_item ? $fulfillment_item->get_quantity() : 0; ?>">
      </div>
      <div class="form-group">
        <label for="unit_cost">Unit Cost:</label>
        <input type="hidden" name="unit_cost_original" value="<?= $fulfillment_item?->get_unit_cost(); ?>">
        <input type="number" step=".01" id="unit_cost" class="form-control form-control-sm" name="unit_cost" value="<?= $fulfillment_item ? $fulfillment_item->get_unit_cost() : 0; ?>">
      </div>
      <div class="form-group">
        <label for="other_cost">Other Cost:</label>
        <input type="hidden" name="other_cost_original" value="<?= $fulfillment_item?->get_other_cost(); ?>">
        <input type="number" step=".01" id="other_cost" class="form-control form-control-sm" name="other_cost" value="<?= $fulfillment_item ? $fulfillment_item->get_other_cost() : 0; ?>">
      </div>
      <div class="form-group">
        <label for="payment_term">Payment Term:</label>
        <input type="hidden" name="payment_term_original" value="<?= $fulfillment_item?->get_payment_term(); ?>">
        <select class="custom-select" name="payment_term" id="payment_term">
          <?php foreach ($payment_terms as $key => $payment_term) : ?>
            <option <?= $payment_term->get_payment_term() == $fulfillment_item?->get_payment_term() ? 'selected' : ''; ?>><?= $payment_term->get_payment_term(); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php if ($_POST["isPartialInvoices"]) : ?>
        <div class="form-group">
          <label for="invoice">Invoice:</label>
          <select class="custom-select" name="invoice">
            <option value="">Choose an option</option>
            <?php foreach ($invoices as $key => $invoice) : ?>
              <option value="<?= $invoice->get_id() ?>" <?= $fulfillment_item?->getIdInvoice() == $invoice->get_id() ? 'selected' : '' ?>><?= $invoice->get_name(); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php endif; ?>
      <div class="form-group">
        <label for="transaction_date">Transaction Date:</label>
        <input type="text" id="transaction_date" readonly class="form-control form-control-sm" name="transaction_date" value="<?= date("m/d/Y", strtotime($fulfillment_item?->getTransactionDate() ?? date("m/d/Y"))) ?>">
      </div>
      <div class="form-group">
        <label for="comment">Comment:</label>
        <textarea id="comment" name="comment" class="form-control form-control-sm" rows="5"><?= $fulfillment_item?->get_comments(); ?></textarea>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="id_fulfillment_item" name="id_fulfillment_item" value="<?= $fulfillment_item?->get_id(); ?>">
<input type="hidden" id="id_item" name="id_item" value="<?= $_POST["idItem"] ?? $fulfillment_item?->get_id_item(); ?>">
<div class="modal-footer">
  <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>