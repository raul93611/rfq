<div class="modal fade" id="nuevo_comment" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="background:var(--color-dark);border-bottom:none;padding:16px 20px;">
        <h5 class="modal-title" style="color:#fff;font-family:'Manrope',sans-serif;font-size:15px;font-weight:700;">
          <i class="fas fa-comment-alt mr-2"></i> Add Comment
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:0.7;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body user-form" style="padding:20px;">
        <form id="form_nuevo_comment" method="post" enctype="multipart/form-data" action="<?= GUARDAR_COMMENT; ?>">
          <div class="form-group mb-0">
            <label for="comment_rfq">Comment</label>
            <textarea class="form-control form-control-sm" name="comment_rfq" rows="7" id="comment_rfq" autofocus required placeholder="Enter your comment here..."></textarea>
          </div>
          <input type="hidden" name="id_rfq" value="<?= htmlspecialchars($cotizacion_recuperada->obtener_id()); ?>">
          <input type="hidden" name="place" value="quote">
        </form>
      </div>
      <div class="modal-footer" style="border-top:1px solid #f0f2f5;padding:12px 20px;justify-content:flex-end;gap:8px;">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          <i class="fa fa-times mr-1"></i> Cancel
        </button>
        <button type="submit" name="guardar_comment" form="form_nuevo_comment" class="btn btn-primary btn-sm">
          <i class="fa fa-check mr-1"></i> Save Comment
        </button>
      </div>
    </div>
  </div>
</div>