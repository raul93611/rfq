<div class="modal fade" id="new_tracking" tabindex="-1" role="dialog" aria-labelledby="newTrackingLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newTrackingLabel">Add Tracking</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_tracking_form" method="post" enctype="multipart/form-data" action="<?= htmlspecialchars(SAVE_TRACKING); ?>">

          <!-- Quantity Shipped -->
          <div class="form-group">
            <label for="quantity_shipped">Quantity (shipped):</label>
            <input type="number" step="1" class="form-control form-control-sm" name="quantity_shipped" id="quantity_shipped" value="0" min="0" aria-describedby="quantityHelp">
            <small id="quantityHelp" class="form-text text-muted">Enter the number of items shipped.</small>
          </div>

          <!-- Delivery Date -->
          <div class="form-group">
            <label for="delivery_date">Delivery Date:</label>
            <input type="text" id="delivery_date" class="form-control form-control-sm" name="delivery_date" aria-describedby="deliveryHelp">
            <small id="deliveryHelp" class="form-text text-muted">Select the delivery date.</small>
          </div>

          <!-- Due Date -->
          <div class="form-group">
            <label for="due_date">Due Date:</label>
            <input type="text" id="due_date" class="form-control form-control-sm" name="due_date" aria-describedby="dueDateHelp">
            <small id="dueDateHelp" class="form-text text-muted">Select the due date.</small>
          </div>

          <!-- Carrier -->
          <div class="form-group">
            <label for="carrier">Carrier:</label>
            <input type="text" id="carrier" name="carrier" class="form-control form-control-sm" value="" aria-describedby="carrierHelp">
            <small id="carrierHelp" class="form-text text-muted">Enter the carrier's name.</small>
          </div>

          <!-- Tracking Number -->
          <div class="form-group">
            <label for="tracking_number">Tracking #:</label>
            <textarea class="form-control form-control-sm" name="tracking_number" rows="5" id="tracking_number" aria-describedby="trackingHelp"></textarea>
            <small id="trackingHelp" class="form-text text-muted">Enter the tracking number.</small>
          </div>

          <!-- Signed By -->
          <div class="form-group">
            <label for="signed_by">Signed By:</label>
            <input type="text" id="signed_by" name="signed_by" class="form-control form-control-sm" value="" aria-describedby="signedByHelp">
            <small id="signedByHelp" class="form-text text-muted">Enter the name of the person who signed for the shipment.</small>
          </div>

          <!-- Comments -->
          <div class="form-group">
            <label for="comments">Comment:</label>
            <textarea class="form-control form-control-sm" name="comments" rows="5" id="comments" aria-describedby="commentsHelp"></textarea>
            <small id="commentsHelp" class="form-text text-muted">Add any additional comments or notes.</small>
          </div>

          <!-- Hidden Input for Item ID -->
          <input type="hidden" id="id_item" name="id_item" value="<?= htmlspecialchars($id_item ?? ''); ?>">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="save_tracking" form="add_tracking_form" class="btn btn-success">
          <i class="fa fa-check"></i> Save
        </button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">
          <i class="fa fa-ban"></i> Cancel
        </button>
      </div>
    </div>
  </div>
</div>