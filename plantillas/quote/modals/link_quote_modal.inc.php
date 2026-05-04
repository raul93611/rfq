<div class="modal fade" id="link_quote_modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="background:var(--color-dark);border-bottom:none;padding:16px 20px;">
        <h5 class="modal-title" style="color:#fff;font-family:'Manrope',sans-serif;font-size:15px;font-weight:700;">
          <i class="fas fa-link mr-2"></i> Set Master Proposal
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:0.7;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body user-form" style="padding:20px;">
        <form action="<?= LINK_QUOTE; ?>" id="link_quote_form" method="post">
          <div class="form-group mb-1">
            <label for="quote_ids">Proposal</label>
            <select id="quote_ids" name="master" class="form-control form-control-sm" style="width:100%;" required>
              <option value="" disabled selected>Search by proposal ID...</option>
            </select>
            <input type="hidden" name="id_rfq" value="<?= htmlspecialchars($id_rfq); ?>">
          </div>
          <small class="text-muted" style="font-size:11px;">
            <i class="fas fa-info-circle mr-1"></i> Type at least 4 characters to search.
          </small>
        </form>
      </div>
      <div class="modal-footer" style="border-top:1px solid #f0f2f5;padding:12px 20px;justify-content:flex-end;gap:8px;">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          <i class="fa fa-times mr-1"></i> Cancel
        </button>
        <button type="submit" name="submit" form="link_quote_form" class="btn btn-primary btn-sm">
          <i class="fa fa-link mr-1"></i> Link Proposal
        </button>
      </div>
    </div>
  </div>
</div>