<div class="modal fade" id="add-shared-event-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Shared Event</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-shared-event-form" method="post" enctype="multipart/form-data" action="">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" class="form-control form-control-sm" name="name" value="" required>
                <small class="form-text text-muted">Enter the name of the shared event.</small>
              </div>
              <div class="form-group">
                <label for="start">Start:</label>
                <input type="text" id="start" class="form-control form-control-sm" name="start" value="" readonly required>
                <small class="form-text text-muted">Select the starting date and time for the event.</small>
              </div>
              <div class="form-group">
                <label for="end">End:</label>
                <input type="text" id="end" class="form-control form-control-sm" name="end" value="" readonly required>
                <small class="form-text text-muted">Select the ending date and time for the event.</small>
              </div>
              <div class="form-group">
                <label for="color">Color:</label>
                <div class="input-group">
                  <input type="text" id="color" class="form-control form-control-sm" name="color" value="" required>
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-square"></i></span>
                  </div>
                </div>
                <small class="form-text text-muted">Choose a color to represent the event in the calendar.</small>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="add-shared-event-form" class="btn btn-primary">
          <i class="fa fa-check"></i> Save
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fa fa-ban"></i> Cancel
        </button>
      </div>
    </div>
  </div>
</div>