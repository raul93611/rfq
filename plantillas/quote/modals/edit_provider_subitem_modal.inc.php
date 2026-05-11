<div class="modal fade" id="edit-provider-subitem-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered iem-dialog iem-dialog-sm" role="document">
    <div class="modal-content iem-content">

      <div class="modal-header iem-header">
        <div class="iem-header-left">
          <div class="iem-header-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </div>
          <div>
            <div class="iem-title">Edit Provider</div>
            <div class="iem-subtitle" id="edit-provider-subitem-subtitle">Loading…</div>
          </div>
        </div>
        <button type="button" class="iem-close" data-dismiss="modal" aria-label="Close">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <form id="iem-edit-provider-subitem-form" novalidate>
        <div class="modal-body iem-body" id="iem-edit-provider-subitem-body">
          <div class="iem-loading">
            <div class="iem-spinner"></div>
          </div>
        </div>
        <div class="modal-footer iem-footer">
          <div class="iem-footer-row" style="width:100%;">
            <div id="iem-edit-psi-warn" class="iem-footer-warn" style="display:none;width:100%;margin-bottom:8px;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
              <span><strong>Unsaved changes.</strong> Close without saving?</span>
              <div class="iem-footer-warn-actions">
                <button type="button" class="iem-btn iem-btn-xs iem-keep-editing">Keep editing</button>
                <button type="button" class="iem-btn iem-btn-xs iem-btn-discard" data-dismiss="modal">Discard</button>
              </div>
            </div>
          </div>
          <div class="iem-footer-row" style="width:100%;justify-content:space-between;">
            <button type="button" class="iem-btn iem-btn-danger iem-delete-provider-subitem-btn">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
              Delete
            </button>
            <div style="display:flex;gap:8px;">
              <button type="button" class="iem-btn iem-cancel-btn" data-dismiss="modal">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Cancel
              </button>
              <button type="button" class="iem-btn iem-btn-primary iem-save-btn">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                Save Changes
              </button>
            </div>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>
