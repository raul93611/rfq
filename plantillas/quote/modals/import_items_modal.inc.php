<div class="modal fade" id="import-items-modal" tabindex="-1" role="dialog" aria-hidden="true"
     data-fulfillment="<?= $cotizacion_recuperada->obtener_fullfillment() ? '1' : '0'; ?>">
  <div class="modal-dialog modal-dialog-centered iem-dialog iem-dialog-sm" role="document">
    <div class="modal-content iem-content">

      <div class="modal-header iem-header">
        <div class="iem-header-left">
          <div class="iem-header-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
          </div>
          <div>
            <div class="iem-title">Import Items</div>
            <div class="iem-subtitle">
              Proposal #<?= htmlspecialchars($cotizacion_recuperada->obtener_id(), ENT_QUOTES, 'UTF-8'); ?><?= $cotizacion_recuperada->obtener_fullfillment() ? ' · Fulfillment stage' : ''; ?>
            </div>
          </div>
        </div>
        <button type="button" class="iem-close" data-dismiss="modal" aria-label="Close">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <form id="import-items-form" action="<?= IMPORT_ITEMS; ?>" method="post" enctype="multipart/form-data">
        <div class="modal-body iem-body">

          <div class="iem-field">
            <div class="ii-field-head">
              <label class="iem-label">Spreadsheet File</label>
              <a class="ii-template-link" href="<?= IMPORT_ITEMS_TEMPLATE; ?>" data-testid="ii-download-template">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download Template
              </a>
            </div>

            <div class="ii-dropzone" id="ii-dropzone" data-testid="ii-dropzone">
              <input type="file" id="uploaded_file" name="uploaded_file" accept=".csv,.xls,.xlsx" style="display:none;">
              <div class="ii-dropzone-cue">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg>
                <div>
                  <div class="ii-dropzone-title">Drag a file here or <span class="ii-dropzone-link">browse</span></div>
                  <div class="ii-dropzone-sub">CSV, XLS, or XLSX — up to 5 provider price columns per item</div>
                </div>
              </div>
            </div>

            <div class="ii-file-chip" id="ii-file-chip" data-testid="ii-file-chip" style="display:none;">
              <span class="ii-file-badge" id="ii-file-badge">FILE</span>
              <span class="ii-file-name" id="ii-file-name"></span>
              <span class="ii-file-size" id="ii-file-size"></span>
              <button type="button" class="ii-file-remove" id="ii-file-remove" aria-label="Remove file" data-testid="ii-file-remove">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </div>
          </div>

          <div class="iem-field">
            <label class="iem-label">Import Mode</label>
            <div class="ii-radio-group" role="radiogroup" aria-label="Import mode">
              <label class="ii-radio is-selected" id="ii-radio-append">
                <input type="radio" class="ii-radio-input" name="import_mode" value="append" checked data-testid="ii-mode-append">
                <span class="ii-radio-dot" aria-hidden="true"></span>
                <span class="ii-radio-text">
                  <span class="ii-radio-label">Add to existing items</span>
                  <span class="ii-radio-help">Items from the file are added alongside what's already in this quote.</span>
                </span>
              </label>
              <label class="ii-radio" id="ii-radio-replace">
                <input type="radio" class="ii-radio-input" name="import_mode" value="replace" data-testid="ii-mode-replace">
                <span class="ii-radio-dot" aria-hidden="true"></span>
                <span class="ii-radio-text">
                  <span class="ii-radio-label">Replace existing items</span>
                  <span class="ii-radio-help">Removes all current items and providers, then adds the file's contents in their place.</span>
                </span>
              </label>
            </div>

            <div class="ii-warning-collapse" id="ii-warning-collapse">
              <div>
                <div class="ii-warning" data-testid="ii-fulfillment-warning">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                  <span><strong>Heads up —</strong> this quote is in Fulfillment. Existing fulfillment and invoice records reference these items, and replacing them may leave that data pointing at items that no longer exist.</span>
                </div>
              </div>
            </div>
          </div>

          <input type="hidden" name="id_rfq" value="<?= htmlspecialchars($cotizacion_recuperada->obtener_id()); ?>">
          <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($_SESSION['user']->obtener_id()); ?>">
        </div>

        <div class="modal-footer iem-footer">
          <div class="iem-footer-row" style="width:100%;">
            <div class="iem-footer-meta" id="ii-footer-meta"></div>
            <button type="button" class="iem-btn iem-cancel-btn" data-dismiss="modal">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              Cancel
            </button>
            <button type="submit" class="iem-btn iem-btn-primary" id="ii-upload-btn" data-testid="ii-upload-btn" disabled>
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
              Upload File
            </button>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>
