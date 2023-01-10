<div class="modal fade" id="edit_service_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Service</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="" id="edit_service_form" action="<?php echo UPDATE_SERVICE_RE_QUOTE; ?>" method="post">

        </form>
        <input type="hidden" value="<?php echo $id_rfq; ?>" name="id_rfq" form="edit_service_form">
      </div>
      <div class="modal-footer">
        <button type="submit" name="edit_service_button" form="edit_service_form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
