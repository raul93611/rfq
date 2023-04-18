<div class="modal fade" id="edit_invoice_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Rename Invoice</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="edit_invoice_form" method="post" enctype="multipart/form-data" action="<?php echo UPDATE_INVOICE; ?>">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" class="form-control form-control-sm" name="name" value="<?php echo $invoice-> get_name(); ?>">
              </div>
            </div>
          </div>
          <input type="hidden" name="id_invoice" value="<?php echo $id_invoice; ?>">
          <input type="hidden" name="id_rfq" value="<?php echo $invoice-> get_id_rfq(); ?>">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="update_invoice" form="edit_invoice_form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
