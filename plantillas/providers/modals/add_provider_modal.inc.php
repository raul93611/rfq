<div class="modal fade" id="add_provider_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-plus mr-2"></i>Add Provider</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body user-form">
        <form id="add_provider_form" method="post" action="">
          <div class="form-group">
            <label for="name">Provider Name</label>
            <input type="text" id="name" class="form-control" name="name" placeholder="Enter provider name" required>
            <small class="form-text text-muted">Must be unique and cannot be empty.</small>
            <small class="text-danger error_message" style="display:none;">Name cannot be empty and must be unique.</small>
          </div>
        </form>
      </div>
      <div class="modal-footer d-flex justify-content-end" style="gap:8px;">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          <i class="fa fa-times mr-1"></i>Cancel
        </button>
        <button type="submit" name="save" form="add_provider_form" class="btn btn-primary btn-sm">
          <i class="fa fa-check mr-1"></i>Save
        </button>
      </div>
    </div>
  </div>
</div>
