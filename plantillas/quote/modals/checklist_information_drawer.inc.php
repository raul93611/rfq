<?php
// Reuses forms/quote/checklist.inc.php and forms/quote/information.inc.php as-is, each
// wrapped in its own <form> here so both stay mounted (and their fields keep unsaved
// edits) while the drawer is open and the user switches tabs. $cotizacion_recuperada
// and $id_rfq come from the including page (plantillas/quote/editar_cotizacion.inc.php).
$quote = $cotizacion_recuperada;
?>
<div id="qed-drawer-scrim" class="qed-drawer-scrim"></div>
<aside id="qed-drawer" class="qed-drawer" role="dialog" aria-modal="true" aria-hidden="true" aria-label="Checklist and Information">
  <div class="qed-drawer-head">
    <div class="qed-drawer-head-top">
      <div class="qed-drawer-eyebrow">Proposal #<?= htmlspecialchars($quote->obtener_id(), ENT_QUOTES, 'UTF-8'); ?></div>
      <button type="button" id="qed-drawer-close" class="qed-drawer-close" aria-label="Close drawer">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="qed-tabs">
      <button type="button" class="qed-tab is-active" data-tab="checklist" id="qed-tab-checklist">
        Checklist <span class="qed-tab-count" id="qed-tab-checklist-count"><?= (int)$quote->getChecklistCompletionCount(); ?>/10</span>
      </button>
      <button type="button" class="qed-tab" data-tab="information" id="qed-tab-information">Information</button>
    </div>
  </div>

  <div class="qed-tab-panel" data-tab-panel="checklist">
    <div class="qed-panel-topbar">
      <span class="qed-panel-topbar-label">Bid Requirements Checklist</span>
      <a target="_blank" href="<?= htmlspecialchars(GENERATE_CHECKLIST_PDF . $id_rfq, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary btn-sm">
        <i class="fas fa-file-pdf mr-1"></i> PDF
      </a>
    </div>
    <div class="qed-panel-scroll" id="qed-checklist-scroll">
      <div class="qed-error-banner" id="qed-checklist-error" style="display:none;">
        <i class="fas fa-exclamation-circle"></i>
        <span class="qed-error-text"></span>
        <button type="button" class="qed-error-dismiss" data-error-for="checklist" aria-label="Dismiss error"><i class="fas fa-times"></i></button>
      </div>
      <form id="checklist_form" method="post" autocomplete="off" action="<?= htmlspecialchars(SAVE_CHECKLIST . $id_rfq, ENT_QUOTES, 'UTF-8'); ?>">
        <?php include 'forms/quote/checklist.inc.php'; ?>
      </form>
    </div>
    <div class="qed-tab-foot">
      <span class="qed-save-pill" id="qed-checklist-save-pill"><i class="fas fa-check"></i> Saved</span>
      <button type="submit" class="btn btn-primary btn-sm qed-btn-save" id="qed-checklist-save-btn" form="checklist_form" name="save_checklist">
        <i class="fas fa-check mr-1"></i> Save Checklist
      </button>
    </div>
  </div>

  <div class="qed-tab-panel is-hidden" data-tab-panel="information">
    <div class="qed-panel-topbar">
      <span class="qed-panel-topbar-label">Contract &amp; Bid Information</span>
    </div>
    <div class="qed-panel-scroll" id="qed-information-scroll">
      <div class="qed-error-banner" id="qed-information-error" style="display:none;">
        <i class="fas fa-exclamation-circle"></i>
        <span class="qed-error-text"></span>
        <button type="button" class="qed-error-dismiss" data-error-for="information" aria-label="Dismiss error"><i class="fas fa-times"></i></button>
      </div>
      <form id="information_form" method="post" autocomplete="off" action="<?= htmlspecialchars(SAVE_INFORMATION . $id_rfq, ENT_QUOTES, 'UTF-8'); ?>">
        <?php include 'forms/quote/information.inc.php'; ?>
      </form>
    </div>
    <div class="qed-tab-foot">
      <span class="qed-save-pill" id="qed-information-save-pill"><i class="fas fa-check"></i> Saved</span>
      <button type="submit" class="btn btn-primary btn-sm qed-btn-save" id="qed-information-save-btn" form="information_form" name="save_information">
        <i class="fas fa-check mr-1"></i> Save Information
      </button>
    </div>
  </div>
</aside>

<div class="qed-confirm-overlay" id="qed-confirm-overlay" style="display:none;">
  <div class="qed-confirm-card">
    <div class="qed-confirm-icon"><i class="fas fa-exclamation-triangle"></i></div>
    <div class="qed-confirm-title">Discard unsaved changes?</div>
    <div class="qed-confirm-sub">You have edits that haven't been saved. Closing now will discard them.</div>
    <div class="qed-confirm-actions">
      <button type="button" class="btn btn-secondary btn-sm" id="qed-confirm-cancel">Cancel</button>
      <button type="button" class="btn btn-danger btn-sm" id="qed-confirm-discard">
        <i class="fas fa-trash mr-1"></i> Discard
      </button>
    </div>
  </div>
</div>
