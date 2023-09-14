<div class="modal fade" id="add-personnel-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-personnel-form" method="post" enctype="multipart/form-data" action="">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" class="form-control form-control-sm" name="name" value="">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="add-personnel-form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
