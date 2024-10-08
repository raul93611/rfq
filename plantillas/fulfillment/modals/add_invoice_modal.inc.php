<div class="modal fade" id="add_invoice_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Invoice</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_invoice_form" method="post">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" class="form-control form-control-sm" name="name" value="<?php echo $id_rfq . '-'; ?>">
              </div>
              <div class="form-group">
                <label for="created_at">Created At:</label>
                <input type="text" id="created_at" readonly class="form-control form-control-sm" name="created_at">
              </div>
            </div>
          </div>
          <div id="error"></div>
          <input type="hidden" name="id_rfq" value="<?php echo $id_rfq; ?>">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="save_invoice" form="add_invoice_form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
