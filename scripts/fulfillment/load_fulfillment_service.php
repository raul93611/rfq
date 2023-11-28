<?php
Conexion::abrir_conexion();
$fulfillment_service = FulfillmentServiceRepository::get_one(Conexion::obtener_conexion(), $id_fulfillment_service);
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
        <select name="provider" class="custom-select">
          <?php foreach ($providers_list as $key => $provider) : ?>
            <option <?= $provider->get_company_name() == $fulfillment_service->get_provider() ? 'selected' : ''; ?>><?= $provider->get_company_name(); ?></option>
          <?php endforeach; ?>
        </select>
        <input type="hidden" name="provider_original" value="<?= $fulfillment_service->get_provider(); ?>">
      </div>
      <div class="form-group">
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" class="form-control form-control-sm" name="quantity" value="<?= $fulfillment_service->get_quantity(); ?>">
        <input type="hidden" name="quantity_original" value="<?= $fulfillment_service->get_quantity(); ?>">
      </div>
      <div class="form-group">
        <label for="unit_cost">Unit Cost:</label>
        <input type="number" step=".01" id="unit_cost" class="form-control form-control-sm" name="unit_cost" value="<?= $fulfillment_service->get_unit_cost(); ?>">
        <input type="hidden" name="unit_cost_original" value="<?= $fulfillment_service->get_unit_cost(); ?>">
      </div>
      <div class="form-group">
        <label for="other_cost">Other Cost:</label>
        <input type="number" step=".01" id="other_cost" class="form-control form-control-sm" name="other_cost" value="<?= $fulfillment_service->get_other_cost(); ?>">
        <input type="hidden" name="other_cost_original" value="<?= $fulfillment_service->get_other_cost(); ?>">
      </div>
      <div class="form-group">
        <label for="payment_term">Payment Term:</label>
        <select class="custom-select" name="payment_term" id="payment_term">
          <?php foreach ($payment_terms as $key => $payment_term) : ?>
            <option <?= $payment_term->get_payment_term() == $fulfillment_service->get_payment_term() ? 'selected' : ''; ?>><?= $payment_term->get_payment_term(); ?></option>
          <?php endforeach; ?>
        </select>
        <input type="hidden" name="payment_term_original" value="<?= $fulfillment_service->get_payment_term(); ?>">
      </div>
      <?php if ($_POST["isPartialInvoices"]) : ?>
        <div class="form-group">
          <label for="invoice">Invoice:</label>
          <select class="custom-select" name="invoice">
            <option value="">Choose an option</option>
            <?php foreach ($invoices as $key => $invoice) : ?>
              <option value="<?= $invoice->get_id() ?>" <?= $fulfillment_service->getIdInvoice() == $invoice->get_id() ? 'selected' : '' ?>><?= $invoice->get_name(); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php endif; ?>
      <div class="form-group">
        <label for="comment">Comment:</label>
        <textarea id="comment" name="comment" rows="5" class="form-control form-control-sm"><?= $fulfillment_service->getComments(); ?></textarea>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="id_fulfillment_service" name="id_fulfillment_service" value="<?= $fulfillment_service->get_id(); ?>">
<input type="hidden" id="id_service" name="id_service" value="<?= $fulfillment_service->get_id_service(); ?>">
<div class="modal-footer">
  <button type="submit" name="save_edit_fulfillment_service_button" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>