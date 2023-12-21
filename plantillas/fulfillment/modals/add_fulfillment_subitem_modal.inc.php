<div class="modal fade" id="new_fulfillment_subitem_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_fulfillment_subitem_form" method="post" enctype="multipart/form-data" action="">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Provider:</label>
                <select name="provider" class="custom-select">
                  <?php foreach ($providers_list as $key => $provider) : ?>
                    <option><?= $provider->get_company_name(); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" class="form-control form-control-sm" name="quantity" value="">
              </div>
              <div class="form-group">
                <label for="unit_cost">Unit Cost:</label>
                <input type="number" step=".01" id="unit_cost" class="form-control form-control-sm" name="unit_cost" value="">
              </div>
              <div class="form-group">
                <label for="other_cost">Other Cost:</label>
                <input type="number" step=".01" id="other_cost" class="form-control form-control-sm" name="other_cost" value="0">
              </div>
              <div class="form-group">
                <label for="payment_term">Payment Term:</label>
                <select class="custom-select" name="payment_term">
                  <?php foreach ($payment_terms as $key => $payment_term) : ?>
                    <option><?= $payment_term->get_payment_term(); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <?php if ($quote->obtener_fulfillment_pending()) : ?>
                <div class="form-group">
                  <label for="invoice">Invoice:</label>
                  <select class="custom-select" name="invoice">
                    <option value="">Choose an option</option>
                    <?php foreach ($invoices as $key => $invoice) : ?>
                      <option value="<?= $invoice->get_id() ?>"><?= $invoice->get_name(); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              <?php endif; ?>
              <div class="form-group">
                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment" rows="5" class="form-control form-control-sm"></textarea>
              </div>
            </div>
          </div>
          <input type="hidden" id="id_subitem" name="id_subitem" value="">
          <input type="hidden" id="id_rfq" name="id_rfq" value="<?= $quote->obtener_id(); ?>">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="save_fulfillment_subitem" form="add_fulfillment_subitem_form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>