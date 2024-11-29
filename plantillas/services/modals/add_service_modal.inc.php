<div class="modal fade" id="add_service_modal" tabindex="-1" role="dialog" aria-labelledby="addServiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="addServiceModalLabel">Add Service</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <?php include_once 'forms/service/add_service_form.inc.php'; ?>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="submit" form="add_service_form" name="add_service_button" class="btn btn-success">
          <i class="fa fa-check"></i> Save
        </button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">
          <i class="fa fa-ban"></i> Cancel
        </button>
      </div>

    </div>
  </div>
</div>