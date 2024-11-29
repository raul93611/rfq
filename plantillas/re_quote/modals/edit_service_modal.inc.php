<div class="modal fade" id="edit_service_modal" tabindex="-1" role="dialog" aria-labelledby="editServiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editServiceModalLabel">Edit Service</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="edit_service_form" action="<?= UPDATE_SERVICE_RE_QUOTE; ?>" method="post">
          <!-- Add form fields here -->
        </form>
        <input type="hidden" name="id_rfq" value="<?= $id_rfq; ?>">
      </div>
      <div class="modal-footer">
        <button type="submit" form="edit_service_form" class="btn btn-success">
          <i class="fa fa-check"></i> Save
        </button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">
          <i class="fa fa-ban"></i> Cancel
        </button>
      </div>
    </div>
  </div>
</div>