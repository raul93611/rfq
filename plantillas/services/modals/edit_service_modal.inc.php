<div class="modal fade" id="edit_service_modal" tabindex="-1" role="dialog" aria-labelledby="editServiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="editServiceModalLabel">Edit Service</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <form id="edit_service_form" action="<?= EDIT_SERVICE; ?>" method="post">
          <!-- Form content can go here, e.g., input fields for editing the service -->
        </form>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="submit" form="edit_service_form" name="edit_service_button" class="btn btn-success">
          <i class="fa fa-check"></i> Save
        </button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">
          <i class="fa fa-ban"></i> Cancel
        </button>
      </div>

    </div>
  </div>
</div>