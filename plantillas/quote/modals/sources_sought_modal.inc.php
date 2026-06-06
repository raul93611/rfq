<div class="modal fade" id="sources-sought-modal" role="dialog" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="background:var(--color-dark);border-bottom:none;padding:16px 20px;">
        <h5 class="modal-title" style="color:#fff;font-family:'Manrope',sans-serif;font-size:15px;font-weight:700;">
          <i class="fas fa-paper-plane mr-2"></i> Submit Proposal
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:0.7;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="padding:20px;">
        <p style="margin:0 0 16px;font-size:14px;color:#39485a;line-height:1.6;">
          Is this a regular submission or a <strong>Sources Sought</strong> response?
        </p>
        <div class="ss-choice-grid">
          <button type="button" id="ss-regular-btn" class="ss-choice">
            <span class="ss-choice-ico" style="--c:#4f6ef0;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2 11 13"/><path d="M22 2 15 22l-4-9-9-4 20-7z"/></svg>
            </span>
            <span class="ss-choice-title">Regular submission</span>
            <span class="ss-choice-sub">A normal bid submission.</span>
          </button>
          <button type="button" id="ss-sources-btn" class="ss-choice">
            <span class="ss-choice-ico" style="--c:#14b8a6;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><line x1="20" y1="20" x2="16.5" y2="16.5"/></svg>
            </span>
            <span class="ss-choice-title">Sources Sought</span>
            <span class="ss-choice-sub">A capability/RFI response — excluded from win/loss.</span>
          </button>
        </div>
      </div>
      <div class="modal-footer" style="border-top:1px solid #f0f2f5;padding:12px 20px;justify-content:flex-end;">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          <i class="fa fa-times mr-1"></i> Cancel
        </button>
      </div>
    </div>
  </div>
</div>
