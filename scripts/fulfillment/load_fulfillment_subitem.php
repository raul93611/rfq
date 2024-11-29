<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

$fulfillment_subitem = !empty($_POST["idFulfillmentSubitem"]) ? FulfillmentSubitemRepository::get_one($conexion, $_POST["idFulfillmentSubitem"]) : null;
$providers_list = ProviderListRepository::get_all($conexion);
$payment_terms = PaymentTermRepository::get_all($conexion);
$invoices = InvoiceRepository::get_all_by_id_rfq($conexion, $_POST["idRfq"]);

Conexion::cerrar_conexion();
?>

<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label>Provider:</label>
        <input type="hidden" name="provider_original" value="<?= htmlspecialchars($fulfillment_subitem?->get_provider() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <select name="provider" class="custom-select">
          <?php foreach ($providers_list as $provider) : ?>
            <option value="<?= htmlspecialchars($provider->get_company_name(), ENT_QUOTES, 'UTF-8'); ?>" <?= $provider->get_company_name() === $fulfillment_subitem?->get_provider() ? 'selected' : ''; ?>>
              <?= htmlspecialchars($provider->get_company_name(), ENT_QUOTES, 'UTF-8'); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <small class="form-text text-muted">Select the provider for this item.</small>
      </div>

      <div class="form-group">
        <label for="transaction_date">Transaction Date:</label>
        <input type="text" id="transaction_date" readonly class="form-control form-control-sm" name="transaction_date"
          value="<?= $fulfillment_subitem && !empty($fulfillment_subitem->getTransactionDate()) ? date("m/d/Y", strtotime($fulfillment_subitem->getTransactionDate())) : '' ?>">
        <small class="form-text text-muted">Transaction date is auto-filled.</small>
      </div>

      <div class="form-group">
        <label for="quantity">Quantity:</label>
        <input type="hidden" name="quantity_original" value="<?= htmlspecialchars($fulfillment_subitem?->get_quantity() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <input type="number" step=".01" id="quantity" class="form-control form-control-sm" name="quantity"
          value="<?= htmlspecialchars($fulfillment_subitem ? $fulfillment_subitem->get_quantity() : 0, ENT_QUOTES, 'UTF-8'); ?>">
        <small class="form-text text-muted">Enter the quantity of items provided.</small>
      </div>

      <div class="form-group">
        <label for="unit_cost">Unit Cost:</label>
        <input type="hidden" name="unit_cost_original" value="<?= htmlspecialchars($fulfillment_subitem?->get_unit_cost() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <input type="number" step=".01" id="unit_cost" class="form-control form-control-sm" name="unit_cost"
          value="<?= htmlspecialchars($fulfillment_subitem ? $fulfillment_subitem->get_unit_cost() : 0, ENT_QUOTES, 'UTF-8'); ?>">
        <small class="form-text text-muted">Specify the cost per unit.</small>
      </div>

      <div class="form-group">
        <label for="other_cost">Other Cost:</label>
        <input type="hidden" name="other_cost_original" value="<?= htmlspecialchars($fulfillment_subitem?->get_other_cost() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <input type="number" step=".01" id="other_cost" class="form-control form-control-sm" name="other_cost"
          value="<?= htmlspecialchars($fulfillment_subitem ? $fulfillment_subitem->get_other_cost() : 0, ENT_QUOTES, 'UTF-8'); ?>">
        <small class="form-text text-muted">Additional costs, if any.</small>
      </div>

      <div class="form-group">
        <label for="payment_term">Payment Term:</label>
        <input type="hidden" name="payment_term_original" value="<?= htmlspecialchars($fulfillment_subitem?->get_payment_term() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <select class="custom-select" name="payment_term" id="payment_term">
          <?php foreach ($payment_terms as $payment_term) : ?>
            <option value="<?= htmlspecialchars($payment_term->get_payment_term(), ENT_QUOTES, 'UTF-8'); ?>" <?= $payment_term->get_payment_term() === $fulfillment_subitem?->get_payment_term() ? 'selected' : ''; ?>>
              <?= htmlspecialchars($payment_term->get_payment_term(), ENT_QUOTES, 'UTF-8'); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <small class="form-text text-muted">Choose the payment terms agreed upon.</small>
      </div>

      <?php if ($_POST["isPartialInvoices"]) : ?>
        <div class="form-group">
          <label for="invoice">Invoice:</label>
          <select class="custom-select" name="invoice">
            <option value="">Choose an option</option>
            <?php foreach ($invoices as $invoice) : ?>
              <option value="<?= htmlspecialchars($invoice->get_id(), ENT_QUOTES, 'UTF-8'); ?>" <?= $fulfillment_subitem?->getIdInvoice() == $invoice->get_id() ? 'selected' : ''; ?>>
                <?= htmlspecialchars($invoice->get_name(), ENT_QUOTES, 'UTF-8'); ?>
              </option>
            <?php endforeach; ?>
          </select>
          <small class="form-text text-muted">Select an invoice for partial fulfillment.</small>
        </div>
      <?php endif; ?>

      <div class="form-group">
        <label for="comment">Comment:</label>
        <textarea id="comment" name="comment" class="form-control form-control-sm" rows="5"><?= htmlspecialchars($fulfillment_subitem?->get_comments() ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
        <small class="form-text text-muted">Add any relevant notes or comments.</small>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="id_fulfillment_subitem" name="id_fulfillment_subitem" value="<?= htmlspecialchars($fulfillment_subitem?->get_id() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" id="id_subitem" name="id_subitem" value="<?= htmlspecialchars(empty($_POST["idSubitem"]) ? $fulfillment_subitem?->get_id_subitem() : $_POST["idSubitem"], ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" id="id_rfq" name="id_rfq" value="<?= htmlspecialchars($_POST["idRfq"], ENT_QUOTES, 'UTF-8'); ?>">

<div class="modal-footer">
  <button type="submit" name="save_edit_fulfillment_subitem_button" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>