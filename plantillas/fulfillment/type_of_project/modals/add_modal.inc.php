<div class="modal fade" id="add-type-of-project-modal" tabindex="-1" role="dialog" aria-labelledby="addTypeOfProjectLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="addTypeOfProjectLabel">Add New Project Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <form id="add-type-of-project-form" method="post" enctype="multipart/form-data" action="">
          <div class="form-group">
            <label for="name">Project Type Name:</label>
            <input type="text" id="name" class="form-control form-control-sm" name="name" required>
            <small class="form-text text-muted">Enter a unique name for the new project type.</small>
          </div>
        </form>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="submit" form="add-type-of-project-form" class="btn btn-primary">
          <i class="fa fa-check"></i> Save
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fa fa-ban"></i> Cancel
        </button>
      </div>
    </div>
  </div>
</div>