<div class="modal fade" id="new_tracking" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add tracking</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_tracking_form" method="post" enctype="multipart/form-data" action="<?php echo SAVE_TRACKING; ?>">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="quantity_shipped">Quantity(shipped):</label>
                <input type="number" step=".01" class="form-control form-control-sm" name="quantity_shipped" id="quantity_shipped" value="0">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="delivery_date">Delivery date:</label>
                <input type="text" id="delivery_date" class="form-control form-control-sm" name="delivery_date" value="">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="tracking_number">Tracking #:</label>
            <textarea class="form-control form-control-sm" name="tracking_number" rows="5" id="tracking_number"></textarea>
          </div>
          <div class="form-group">
            <label for="signed_by">Signed by:</label>
            <input type="text" id="signed_by" name="signed_by" class="form-control form-control-sm" value="">
          </div>
          <input type="hidden" id="id_item" name="id_item" value="">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="save_tracking" form="add_tracking_form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
