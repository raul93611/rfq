<div class="modal fade" id="edit_tracking_modal" tabindex="-1" role="dialog" aria-labelledby="editTrackingLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="background:var(--color-dark);color:#fff;border:none;">
        <h5 class="modal-title" id="editTrackingLabel" style="font-size:14px;font-weight:700;">
          <i class="fas fa-truck mr-2"></i> Edit Tracking
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:0.7;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body user-form">
        <input type="hidden" form="edit_tracking_form" name="id_rfq" value="<?= htmlspecialchars($quote->obtener_id()); ?>">
        <form id="edit_tracking_form" method="post" action="">
          <!-- Fields loaded dynamically via JS -->
        </form>
      </div>
      <div class="modal-footer" style="border-top:1px solid #f0f0f0;">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          <i class="fa fa-times mr-1"></i> Cancel
        </button>
        <button type="submit" form="edit_tracking_form" class="btn btn-primary btn-sm">
          <i class="fa fa-check mr-1"></i> Save
        </button>
      </div>
    </div>
  </div>
</div>
