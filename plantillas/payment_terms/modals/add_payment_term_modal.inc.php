<div class="modal fade" id="add_payment_term_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Payment Term</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_payment_term_form" method="post" action="">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="name">Payment Term:</label>
                <input type="text" id="name" class="form-control form-control-sm" name="name" required>
                <small class="form-text text-muted">Please enter a unique payment term. It cannot be empty.</small>
                <div class="error_message text-danger mt-1" style="display:none;">
                  Name cannot be empty and must be different from existing ones.
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="save" form="add_payment_term_form" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>