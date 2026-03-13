<div class="modal fade" id="add-type-of-project-modal" tabindex="-1" role="dialog" aria-labelledby="addTypeOfProjectLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addTypeOfProjectLabel"><i class="fas fa-plus mr-2"></i>Add Project Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body user-form">
        <form id="add-type-of-project-form" method="post" enctype="multipart/form-data" action="">
          <div class="form-group">
            <label for="name">Project Type Name</label>
            <input type="text" id="name" class="form-control" name="name" placeholder="e.g. Construction, IT Services" required>
            <small class="form-text text-muted">Enter a unique name for the new project type.</small>
          </div>
        </form>
      </div>
      <div class="modal-footer d-flex justify-content-end" style="gap:8px;">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          <i class="fa fa-times mr-1"></i>Cancel
        </button>
        <button type="submit" form="add-type-of-project-form" class="btn btn-primary btn-sm">
          <i class="fa fa-check mr-1"></i>Save
        </button>
      </div>
    </div>
  </div>
</div>
