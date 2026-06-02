<div class="modal fade" id="ss-break-modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="background:var(--color-dark);border-bottom:none;padding:16px 20px;">
        <h5 class="modal-title" style="color:#fff;font-family:'Manrope',sans-serif;font-size:15px;font-weight:700;">
          <i class="fas fa-unlink mr-2"></i> Break Sheet Sync
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:0.7;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="padding:20px;">
        <p style="margin:0;font-size:14px;color:#39485a;line-height:1.6;">
          This will disconnect this proposal from the SharePoint sheet. Future edits will <strong>no longer update the sheet</strong>.
        </p>
        <p style="margin:10px 0 0;font-size:13px;color:#6c757d;line-height:1.6;">
          The existing row in the sheet will remain as-is. If you want to remove that data, you can do so manually in the sheet.
        </p>
      </div>
      <div class="modal-footer" style="border-top:1px solid #f0f2f5;padding:12px 20px;justify-content:flex-end;gap:8px;">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          <i class="fa fa-times mr-1"></i> Cancel
        </button>
        <button type="button" id="ss-break-confirm-btn" class="btn btn-danger btn-sm">
          <i class="fas fa-unlink mr-1"></i> Break Sync
        </button>
      </div>
    </div>
  </div>
</div>
