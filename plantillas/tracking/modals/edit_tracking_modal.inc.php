<div class="modal fade" id="edit_tracking_modal" tabindex="-1" role="dialog" aria-labelledby="editTrackingLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editTrackingLabel">Edit Tracking</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Form with hidden input properly structured inside -->
      <input type="hidden" form="edit_tracking_form" name="id_rfq" value="<?= htmlspecialchars($quote->obtener_id()); ?>">
      <form id="edit_tracking_form" method="post" action="">
        <!-- Additional form fields can be added here -->
      </form>
    </div>
  </div>
</div>