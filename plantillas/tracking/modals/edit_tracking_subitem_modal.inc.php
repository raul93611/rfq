<div class="modal fade" id="edit_tracking_subitem_modal" tabindex="-1" role="dialog" aria-labelledby="editTrackingSubitemLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editTrackingSubitemLabel">Edit Tracking Subitem</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- Hidden input for RFQ ID inside the form -->
      <input type="hidden" form="edit_tracking_subitem_form" name="id_rfq" value="<?= htmlspecialchars($quote->obtener_id()); ?>">
      <form id="edit_tracking_subitem_form" method="post" action="">
        <!-- Additional form fields for editing the tracking subitem can be added here -->
      </form>
    </div>
  </div>
</div>