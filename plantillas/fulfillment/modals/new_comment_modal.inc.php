<div class="modal fade" id="nuevo_comment" tabindex="-1" role="dialog" aria-labelledby="addCommentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCommentModalLabel">Add Comment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_nuevo_comment" method="post" enctype="multipart/form-data" action="<?php echo GUARDAR_COMMENT; ?>">
          <div class="form-group">
            <label for="comment_rfq">Comment:</label>
            <textarea class="form-control form-control-sm" name="comment_rfq" id="comment_rfq" rows="10" autofocus></textarea>
            <small class="form-text text-muted">Provide detailed feedback or observations related to the fulfillment.</small>
          </div>
          <input type="hidden" name="id_rfq" value="<?php echo $quote->obtener_id(); ?>">
          <input type="hidden" name="place" value="fulfillment">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="guardar_comment" form="form_nuevo_comment" class="btn btn-primary">
          <i class="fa fa-check"></i> Save
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fa fa-ban"></i> Cancel
        </button>
      </div>
    </div>
  </div>
</div>