<div class="modal fade" id="edit_service_modal" tabindex="-1" role="dialog" aria-labelledby="editServiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="background:var(--color-dark);color:#fff;border:none;">
        <h5 class="modal-title" id="editServiceModalLabel" style="font-size:14px;font-weight:700;">
          <i class="fas fa-concierge-bell mr-2"></i> Edit Service
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:0.7;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body user-form">
        <form id="edit_service_form" action="<?= UPDATE_SERVICE_RE_QUOTE; ?>" method="post">
          <!-- Fields loaded dynamically via JS (load_service.php) -->
        </form>
        <input type="hidden" form="edit_service_form" name="id_rfq" value="<?= $id_rfq; ?>">
      </div>
      <div class="modal-footer" style="border-top:1px solid #f0f0f0;">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          <i class="fa fa-times mr-1"></i> Cancel
        </button>
        <button type="submit" name="edit_service_button" form="edit_service_form" class="btn btn-primary btn-sm">
          <i class="fa fa-check mr-1"></i> Save
        </button>
      </div>
    </div>
  </div>
</div>