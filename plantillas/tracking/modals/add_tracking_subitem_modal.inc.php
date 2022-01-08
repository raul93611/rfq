<div class="modal fade" id="new_tracking_subitem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add tracking</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_tracking_subitem_form" method="post" enctype="multipart/form-data" action="<?php echo SAVE_TRACKING_SUBITEM; ?>">
          <div class="form-group">
            <label for="quantity_shipped">Quantity(shipped):</label>
            <input type="number" step=".01" class="form-control form-control-sm" name="quantity_shipped" id="quantity_shipped" value="0">
          </div>
          <div class="form-group">
            <label for="delivery_date">Delivery date:</label>
            <input type="text" id="delivery_date_subitem" class="form-control form-control-sm" name="delivery_date" value="">
          </div>
          <div class="form-group">
            <label for="due_date">Due date:</label>
            <input type="text" id="due_date" class="form-control form-control-sm" name="due_date" value="">
          </div>
          <div class="form-group">
            <label for="carrier">Carrier:</label>
            <input type="text" id="carrier" name="carrier" class="form-control form-control-sm" value="">
          </div>
          <div class="form-group">
            <label for="tracking_number">Tracking #:</label>
            <textarea class="form-control form-control-sm" name="tracking_number" rows="5" id="tracking_number"></textarea>
          </div>
          <div class="form-group">
            <label for="signed_by">Signed by:</label>
            <input type="text" id="signed_by" name="signed_by" class="form-control form-control-sm" value="">
          </div>
          <div class="form-group">
            <label for="comments">Comment:</label>
            <textarea class="form-control form-control-sm" name="comments" rows="5" id="comments"></textarea>
          </div>
          <input type="hidden" id="id_subitem" name="id_subitem" value="">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="save_tracking_subitem" form="add_tracking_subitem_form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
