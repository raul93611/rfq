<div class="modal fade" id="new_tracking_subitem" tabindex="-1" role="dialog" aria-labelledby="newTrackingSubitemLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="background:var(--color-dark);color:#fff;border:none;">
        <h5 class="modal-title" id="newTrackingSubitemLabel" style="font-size:14px;font-weight:700;">
          <i class="fas fa-truck mr-2"></i> Add Tracking Subitem
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:0.7;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body user-form">
        <form id="add_tracking_subitem_form" method="post" enctype="multipart/form-data" action="<?= htmlspecialchars(SAVE_TRACKING_SUBITEM); ?>">

          <div class="form-group">
            <label for="quantity_shipped">Quantity (shipped)</label>
            <input type="number" step="0.01" class="form-control form-control-sm" name="quantity_shipped" id="quantity_shipped" value="0" min="0">
          </div>

          <div class="form-row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="delivery_date_subitem">Delivery Date</label>
                <input type="text" id="delivery_date_subitem" class="form-control form-control-sm" name="delivery_date">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="text" id="due_date" class="form-control form-control-sm" name="due_date">
              </div>
            </div>
          </div>

          <div class="form-row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="carrier">Carrier</label>
                <input type="text" id="carrier" name="carrier" class="form-control form-control-sm">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="signed_by">Signed By</label>
                <input type="text" id="signed_by" name="signed_by" class="form-control form-control-sm">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="tracking_number">Tracking #</label>
            <textarea class="form-control form-control-sm" name="tracking_number" rows="3" id="tracking_number"></textarea>
          </div>

          <div class="form-group">
            <label for="comments">Comments</label>
            <textarea class="form-control form-control-sm" name="comments" rows="3" id="comments"></textarea>
          </div>

          <input type="hidden" id="id_subitem" name="id_subitem" value="<?= htmlspecialchars($id_subitem ?? ''); ?>">
        </form>
      </div>
      <div class="modal-footer" style="border-top:1px solid #f0f0f0;">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          <i class="fa fa-times mr-1"></i> Cancel
        </button>
        <button type="submit" name="save_tracking_subitem" form="add_tracking_subitem_form" class="btn btn-primary btn-sm">
          <i class="fa fa-check mr-1"></i> Save
        </button>
      </div>
    </div>
  </div>
</div>
