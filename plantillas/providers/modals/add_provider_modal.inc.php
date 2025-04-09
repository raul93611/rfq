<div class="modal fade" id="add_provider_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Provider</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_provider_form" method="post" action="">
          <div class="form-group">
            <label for="name">Provider Name:</label>
            <input type="text" id="name" class="form-control form-control-sm" name="name" required>
            <small class="form-text text-muted">Enter the provider's name. It cannot be empty and must be unique.</small>
            <small class="text-danger error_message">
              Name cannot be empty and must be unique.
            </small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" name="save" form="add_provider_form" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>