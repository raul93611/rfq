<div class="modal fade" id="add-subitem-provider-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Subitem Provider</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-subitem-provider-form" role="form" method="post" action="">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="provider">Provider:</label>
                <input type="text" class="form-control form-control-sm" id="provider" name="provider" placeholder="Provider ..." autofocus required>
              </div>
              <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" step=".01" class="form-control form-control-sm" id="price" name="price" required>
              </div>
            </div>
          </div>
          <input type="hidden" name="id_subitem">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" form="add-subitem-provider-form"><i class="fa fa-check"></i> Save</button>
      </div>
    </div>
  </div>
</div>