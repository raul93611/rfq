<div class="modal fade" id="add_invoice_modal" tabindex="-1" role="dialog" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addInvoiceModalLabel">Add Invoice</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_invoice_form" method="post">
          <div class="form-group">
            <label for="name">Invoice Name:</label>
            <input type="text" id="name" class="form-control form-control-sm" name="name" value="<?= htmlspecialchars($id_rfq . '-'); ?>" required>
            <small class="form-text text-muted">Enter a unique name for the invoice (e.g., RFQ number).</small>
          </div>
          <div class="form-group">
            <label for="created_at">Created At:</label>
            <input type="text" id="created_at" readonly class="form-control form-control-sm" name="created_at" value="">
            <small class="form-text text-muted">This field shows the current date and time.</small>
          </div>
          <div id="error"></div>
          <input type="hidden" name="id_rfq" value="<?= htmlspecialchars($id_rfq); ?>">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="save_invoice" form="add_invoice_form" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>