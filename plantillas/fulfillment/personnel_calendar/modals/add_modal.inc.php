<div class="modal fade" id="add-event-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-event-form" method="post" enctype="multipart/form-data" action="">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" class="form-control form-control-sm" name="name" value="">
              </div>
              <div class="form-group">
                <label for="start">Start:</label>
                <input readonly type="text" id="start" class="form-control form-control-sm" name="start" value="">
              </div>
              <div class="form-group">
                <label for="end">End:</label>
                <input readonly type="text" id="end" class="form-control form-control-sm" name="end" value="">
              </div>
              <div class="form-group">
                <label for="color">Color:</label>
                <div class="input-group">
                  <input type="text" id="color" class="form-control form-control-sm" name="color" value="">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-square"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="id_personnel">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="add-event-form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>