<input type="hidden" name="id_rfq" value="<?= htmlspecialchars($cotizacion_recuperada->obtener_id()); ?>">

<div class="card-body user-form">

  <!-- Main Quote Info -->
  <div class="mb-4">

    <!-- Primary fields — 4 equal columns -->
    <div class="quote-info-grid quote-info-primary mb-2">
      <?php
      $primaryFields = [
        ['label' => 'Contract Number', 'value' => $cotizacion_recuperada->obtener_contract_number(),                                                      'icon' => 'fa-file-contract'],
        ['label' => 'Code',            'value' => $cotizacion_recuperada->obtener_email_code(),                                                           'icon' => 'fa-hashtag'],
        ['label' => 'Channel',         'value' => $cotizacion_recuperada->print_channel(),                                                                'icon' => 'fa-broadcast-tower'],
        ['label' => 'Designated User', 'value' => $usuario_designado->obtener_nombres() . ' ' . $usuario_designado->obtener_apellidos(),                 'icon' => 'fa-user'],
      ];
      foreach ($primaryFields as $field): ?>
        <div class="quote-info-cell quote-info-cell--primary">
          <div class="quote-info-label">
            <i class="fas <?= $field['icon']; ?>"></i> <?= $field['label']; ?>
          </div>
          <div class="quote-info-value">
            <?= $field['value'] ?: '<span style="color:#bbb;">—</span>'; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Secondary fields -->
    <?php
    $secondaryFields = [
      ['label' => 'Client',   'value' => $cotizacion_recuperada->obtener_client(),                                    'icon' => 'fa-building'],
      ['label' => 'Bill To',  'value' => nl2br(htmlspecialchars($cotizacion_recuperada->obtener_address())),          'icon' => 'fa-map-marker-alt'],
      ['label' => 'Ship To',  'value' => nl2br(htmlspecialchars($cotizacion_recuperada->obtener_ship_to())),          'icon' => 'fa-shipping-fast'],
    ];
    $refUrl = $cotizacion_recuperada->getReferenceUrl();
    if ($refUrl) {
      $secondaryFields[] = ['label' => 'Reference URL', 'value' => '<a target="_blank" href="' . htmlspecialchars($refUrl) . '">' . htmlspecialchars($refUrl) . '</a>', 'icon' => 'fa-external-link-alt'];
    }
    $popStart = $cotizacion_recuperada->obtener_pop_start_date();
    $popEnd = $cotizacion_recuperada->obtener_pop_end_date();
    if ($popStart || $popEnd) {
      $popValue = ($popStart && $popEnd)
        ? date('n/j/Y', strtotime($popStart)) . ' – ' . date('n/j/Y', strtotime($popEnd))
        : date('n/j/Y', strtotime($popStart ?: $popEnd));
      $secondaryFields[] = ['label' => 'Period of Performance', 'value' => $popValue, 'icon' => 'fa-calendar-alt'];
    }
    ?>
    <div class="quote-info-grid quote-info-secondary">
      <?php foreach ($secondaryFields as $field): ?>
        <div class="quote-info-cell">
          <div class="quote-info-label">
            <i class="fas <?= $field['icon']; ?>"></i> <?= $field['label']; ?>
          </div>
          <div class="quote-info-value quote-info-value--secondary">
            <?= $field['value'] ?: '<span style="color:#bbb;">—</span>'; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>

  <!-- Checklist / Information / Documents entry cards -->
  <?php
  $qedChecklistCount    = $cotizacion_recuperada->getChecklistCompletionCount();
  $qedChecklistTotal    = 10;
  $qedChecklistComplete = $qedChecklistCount === $qedChecklistTotal;

  $qedDocPath  = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $cotizacion_recuperada->obtener_id();
  $qedDocCount = is_dir($qedDocPath) ? count(array_diff(scandir($qedDocPath), ['.', '..'])) : 0;
  ?>
  <div class="qed-status-row mb-3">
    <button type="button" id="qed-open-checklist" class="qed-status-card" data-tab="checklist">
      <span class="qed-status-ring <?= $qedChecklistComplete ? 'is-complete' : ''; ?>" style="--pct:<?= $qedChecklistCount * 10; ?>;">
        <span class="qed-status-ring-inner"><i class="fas <?= $qedChecklistComplete ? 'fa-check' : 'fa-clipboard-list'; ?>"></i></span>
      </span>
      <span class="qed-status-text">
        <span class="qed-status-label">Checklist</span>
        <span class="qed-status-value" id="qed-checklist-status-value"><?= $qedChecklistCount; ?> of <?= $qedChecklistTotal; ?> items complete</span>
      </span>
      <span class="qed-status-chevron"><i class="fas fa-chevron-right"></i></span>
    </button>
    <button type="button" id="qed-open-information" class="qed-status-card" data-tab="information">
      <span class="qed-status-icon"><i class="fas fa-info-circle"></i></span>
      <span class="qed-status-text">
        <span class="qed-status-label">Information</span>
        <span class="qed-status-value">Dates, status &amp; bid requirements</span>
      </span>
      <span class="qed-status-chevron"><i class="fas fa-chevron-right"></i></span>
    </button>
    <button type="button" id="qed-open-documents" class="qed-status-card" data-tab="documents">
      <span class="qed-status-icon"><i class="fas fa-paperclip"></i></span>
      <span class="qed-status-text">
        <span class="qed-status-label">Documents</span>
        <span class="qed-status-value" id="qed-documents-status-value"><?= $qedDocCount; ?> <?= $qedDocCount === 1 ? 'file' : 'files'; ?> attached</span>
      </span>
      <span class="qed-status-chevron"><i class="fas fa-chevron-right"></i></span>
    </button>
  </div>

  <!-- Items Table -->
  <div class="items-section-wrapper" id="items-section-wrapper">
    <?php RepositorioItem::escribir_items($cotizacion_recuperada->obtener_id()); ?>
  </div>

  <!-- Chemonics: manual total price -->
  <?php if ($cotizacion_recuperada->obtener_canal() == 'Chemonics'): ?>
    <div class="form-group mt-3">
      <label style="font-weight:600;font-size:13px;">Total Price</label>
      <input type="number" step=".01" name="total_price_chemonics" class="form-control form-control-sm"
             style="max-width:220px;"
             value="<?= htmlspecialchars($cotizacion_recuperada->obtener_total_price()); ?>">
    </div>
  <?php endif; ?>

  <!-- Services -->
  <?php if ($cotizacion_recuperada->isServices()): ?>
    <?php include_once 'plantillas/services/services.inc.php'; ?>
  <?php endif; ?>

  <!-- Next Step -->
  <div style="background:#f0f7ff;border-left:4px solid var(--color-primary);border-radius:4px;padding:14px 18px;margin-top:24px;">
    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#8896a5;margin-bottom:10px;">
      Next Step
    </div>
    <?php include_once 'forms/quote/templates/status_checkbox.inc.php'; ?>
  </div>

</div>

<!-- Single sticky bar: Back | Totals | Actions -->
<div class="quote-action-bar">

  <div class="quote-action-bar__left">
    <?php include_once 'forms/quote/templates/go_back_button.inc.php'; ?>
  </div>

  <div class="quote-action-bar__right">
    <button type="submit" class="btn btn-primary btn-sm" name="guardar_cambios_cotizacion">
      <i class="fa fa-check mr-1"></i> Save
    </button>
    <?php include_once 'forms/quote/templates/add_item.inc.php'; ?>
    <a href="#" id="add_comment" class="btn btn-secondary btn-sm">
      <i class="fas fa-plus mr-1"></i> Add Comment
    </a>
    <?php include_once 'forms/quote/templates/rooms_button.inc.php'; ?>
    <?php include_once 'forms/quote/templates/actions_button.inc.php'; ?>
    <?php include_once 'forms/quote/templates/downloads_button.inc.php'; ?>
  </div>

  <div class="quote-action-bar__totals">
    <!-- 50/50 split — shown only when the "50% Upfront / 50% on Completion" term is active (quote.js) -->
    <div class="quote-split" id="payment_split_totals" style="display:none;">
      <div class="quote-action-total quote-split-total">
        <span class="quote-action-total__label">Due Upfront <span class="quote-split-pct">50%</span></span>
        <span class="quote-action-total__value quote-split-value" id="bar-due-upfront">$0.00</span>
      </div>
      <div class="quote-action-total quote-split-total">
        <span class="quote-action-total__label">Due on Completion <span class="quote-split-pct">50%</span></span>
        <span class="quote-action-total__value quote-split-value" id="bar-due-completion">$0.00</span>
      </div>
    </div>
    <div class="quote-action-total">
      <span class="quote-action-total__label">Total Price</span>
      <span class="quote-action-total__value" id="bar-total-price">$<?= number_format($cotizacion_recuperada->obtener_quote_total_price(), 2); ?></span>
    </div>
    <div class="quote-action-total">
      <span class="quote-action-total__label">Total Profit</span>
      <span class="quote-action-total__value" id="bar-total-profit">$<?= number_format($cotizacion_recuperada->obtener_quote_profit(), 2); ?></span>
    </div>
    <div class="quote-action-total">
      <span class="quote-action-total__label">Profit %</span>
      <span class="quote-action-total__value" id="bar-profit-pct"><?= number_format($cotizacion_recuperada->obtener_quote_profit_percentage(), 2); ?>%</span>
    </div>
  </div>

</div>
