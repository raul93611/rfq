<div class="modal fade" id="add-item-modal" tabindex="-1" role="dialog" aria-hidden="true" data-rfq-id="<?= (int)$id_rfq; ?>">
  <div class="modal-dialog modal-dialog-centered iem-dialog iem-dialog-xl" role="document">
    <div class="modal-content iem-content">

      <div class="modal-header iem-header">
        <div class="iem-header-left">
          <div class="iem-header-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41L13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><circle cx="7" cy="7" r="1.4" fill="currentColor"/></svg>
          </div>
          <div>
            <div class="iem-title">Add Item</div>
            <div class="iem-subtitle">Proposal #<?= htmlspecialchars($id_rfq, ENT_QUOTES, 'UTF-8'); ?></div>
          </div>
        </div>
        <button type="button" class="iem-close" data-dismiss="modal" aria-label="Close">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <form id="iem-add-item-form" novalidate>
        <div class="modal-body iem-body">
          <?php include_once 'forms/quote/registro_item_vacio.inc.php'; ?>
        </div>
        <div class="modal-footer iem-footer">
          <div class="iem-footer-row" style="width:100%;">
            <div id="iem-add-item-warn" class="iem-footer-warn" style="display:none;width:100%;margin-bottom:8px;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
              <span><strong>Unsaved changes.</strong> Close without saving?</span>
              <div class="iem-footer-warn-actions">
                <button type="button" class="iem-btn iem-btn-xs iem-keep-editing">Keep editing</button>
                <button type="button" class="iem-btn iem-btn-xs iem-btn-discard" data-dismiss="modal">Discard</button>
              </div>
            </div>
          </div>
          <div class="iem-footer-row" style="width:100%;justify-content:flex-end;">
            <button type="button" class="iem-btn iem-btn-primary iem-save-btn">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
              Save Item
            </button>
            <button type="button" class="iem-btn iem-cancel-btn" data-dismiss="modal">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              Cancel
            </button>

          </div>
        </div>
      </form>

    </div>
  </div>
</div>
