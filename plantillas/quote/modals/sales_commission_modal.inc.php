<?php
// Invoice modal. The commission breakdown is read-only and loaded when the modal opens
// (js/quote.js → COMMISSION_PREVIEW); the amount itself is recalculated on the server at Save.
$invoice_date = $cotizacion_recuperada->obtener_invoice_date();
?>
<div class="modal fade" id="sales_commission_modal" tabindex="-1" role="dialog" aria-labelledby="sales_commission_modal_title" aria-hidden="true"
     data-preview-url="<?= htmlspecialchars(COMMISSION_PREVIEW . $id_rfq, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="modal-dialog modal-dialog-centered iem-dialog iem-dialog-sm" role="document" style="max-width: min(560px, 95vw) !important;">
    <div class="modal-content iem-content">

      <div class="modal-header iem-header">
        <div class="iem-header-left">
          <div class="iem-header-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="14" y2="17"/></svg>
          </div>
          <div>
            <div class="iem-title" id="sales_commission_modal_title">Invoice</div>
            <div class="iem-subtitle">Proposal #<?= htmlspecialchars($cotizacion_recuperada->obtener_id(), ENT_QUOTES, 'UTF-8'); ?></div>
          </div>
        </div>
        <button type="button" class="iem-close" id="sales_commission_close" aria-label="Close">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <div class="modal-body iem-body">
        <div class="iem-field">
          <label class="iem-label" for="invoice_date">Invoice Date <span class="ic-req">*</span></label>
          <div class="ic-date-wrap">
            <input type="text" id="invoice_date" form="form_edited_quote" readonly value="<?= htmlspecialchars(is_null($invoice_date) ? date("m/d/Y") : date("m/d/Y", strtotime($invoice_date))) ?>" class="date iem-input" name="invoice_date" placeholder="MM/DD/YYYY">
            <svg class="ic-date-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          </div>
        </div>

        <div id="ic-breakdown" data-testid="ic-breakdown" aria-live="polite">
          <div class="ic-card ic-loading" aria-busy="true" aria-label="Calculating commission" data-testid="ic-loading">
            <div class="ic-head"><span class="ic-title">Sales Commission</span><span class="ic-ro">Calculating…</span></div>
            <div class="ic-kv"><span class="ic-k">Type of Contract</span><i class="ic-sk" style="width:124px;height:14px"></i></div>
            <div class="ic-profit">
              <div class="ic-pgrid is-head"><span></span><span class="n">Profit $</span><span class="n">Profit %</span></div>
              <?php for ($i = 0; $i < 2; $i++): ?>
                <div class="ic-pgrid"><i class="ic-sk" style="width:92px;height:13px"></i><span class="n"><i class="ic-sk" style="width:84px;height:13px;margin-left:auto"></i></span><span class="n"><i class="ic-sk" style="width:52px;height:13px;margin-left:auto"></i></span></div>
              <?php endfor; ?>
            </div>
            <div class="ic-amount"><div><i class="ic-sk" style="width:78px;height:10px"></i><i class="ic-sk" style="width:168px;height:36px;margin-top:8px"></i></div><i class="ic-sk" style="width:176px;height:28px;border-radius:14px;margin-bottom:4px"></i></div>
            <div class="ic-status"><i class="ic-sk" style="width:150px;height:12px"></i></div>
          </div>
        </div>

        <div class="iem-field">
          <label class="iem-label" for="sales_commission_comment">Comment <span class="ic-opt">(optional)</span></label>
          <textarea class="iem-textarea" name="sales_commission_comment" id="sales_commission_comment" rows="4" form="form_edited_quote" placeholder="Add a comment"></textarea>
        </div>
      </div>

      <div class="modal-footer iem-footer">
        <div class="iem-footer-row">
          <button type="button" class="iem-btn" id="sales_commission_cancel">Cancel</button>
          <button type="submit" name="guardar_cambios_cotizacion" form="form_edited_quote" class="iem-btn iem-btn-primary" id="sales_commission_save" data-testid="ic-save" disabled>
            <i class="fa fa-check"></i> Save
          </button>
        </div>
      </div>

    </div>
  </div>
</div>
