<div class="modal fade" id="nuevo_comment" tabindex="-1" role="dialog" aria-labelledby="addCommentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="background:var(--color-dark);color:#fff;border:none;">
        <h5 class="modal-title"><i class="fas fa-comment-alt mr-2"></i> Add Comment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:0.7;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body user-form">
        <form id="form_nuevo_comment" method="post" enctype="multipart/form-data" action="<?php echo GUARDAR_COMMENT; ?>">
          <div class="form-group">
            <label for="comment_rfq">Comment:</label>
            <textarea class="form-control form-control-sm" name="comment_rfq" id="comment_rfq" rows="10" autofocus></textarea>
          </div>
          <input type="hidden" name="id_rfq" value="<?php echo $quote->obtener_id(); ?>">
          <input type="hidden" name="place" value="fulfillment">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="guardar_comment" form="form_nuevo_comment" class="btn btn-primary btn-sm">
          <i class="fa fa-check"></i> Save
        </button>
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          <i class="fa fa-ban"></i> Cancel
        </button>
      </div>
    </div>
  </div>
</div>