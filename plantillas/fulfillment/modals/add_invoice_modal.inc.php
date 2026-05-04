<div class="modal fade" id="add_invoice_modal" tabindex="-1" role="dialog" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="background:var(--color-dark);color:#fff;border:none;">
        <h5 class="modal-title"><i class="fas fa-file-invoice-dollar mr-2"></i> Add Invoice</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:0.7;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body user-form">
        <form id="add_invoice_form" method="post">
          <div class="form-group">
            <label for="name">Invoice Name:</label>
            <input type="text" id="name" class="form-control form-control-sm" name="name" value="<?= htmlspecialchars($id_rfq . '-'); ?>" required>
          </div>
          <div class="form-group">
            <label for="created_at">Created At:</label>
            <input type="text" id="created_at" readonly class="form-control form-control-sm" name="created_at" value="">
          </div>
          <div id="error"></div>
          <input type="hidden" name="id_rfq" value="<?= htmlspecialchars($id_rfq); ?>">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="save_invoice" form="add_invoice_form" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>