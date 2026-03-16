<div class="modal fade" id="edit_service_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="background:var(--color-dark);border-bottom:none;padding:16px 20px;">
        <h5 class="modal-title" style="color:#fff;font-family:'Manrope',sans-serif;font-size:15px;font-weight:700;">
          <i class="fas fa-concierge-bell mr-2"></i> Edit Service
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:0.7;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="padding:20px;">
        <form id="edit_service_form" action="<?= EDIT_SERVICE; ?>" method="post">
        </form>
      </div>
      <div class="modal-footer" style="border-top:1px solid #f0f2f5;padding:12px 20px;justify-content:flex-end;gap:8px;">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          <i class="fa fa-times mr-1"></i> Cancel
        </button>
        <button type="submit" form="edit_service_form" name="edit_service_button" class="btn btn-primary btn-sm">
          <i class="fa fa-check mr-1"></i> Save Changes
        </button>
      </div>
    </div>
  </div>
</div>