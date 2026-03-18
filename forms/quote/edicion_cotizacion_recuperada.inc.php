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

  <!-- Quick Links -->
  <div class="mb-3" style="display:flex;gap:8px;">
    <a href="<?= CHECKLIST . htmlspecialchars($cotizacion_recuperada->obtener_id()); ?>" class="btn btn-sm btn-outline-secondary">
      <i class="fas fa-clipboard-list mr-1"></i> Checklist
    </a>
    <a href="<?= INFORMATION . htmlspecialchars($cotizacion_recuperada->obtener_id()); ?>" class="btn btn-sm btn-outline-secondary">
      <i class="fas fa-info-circle mr-1"></i> Information
    </a>
  </div>

  <!-- Documents -->
  <div class="mb-4" style="background:#f8f9fa;border-radius:8px;padding:14px 16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
      <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#8896a5;">
        <i class="fas fa-paperclip mr-1"></i> Documents
      </span>
      <button id="download-all" type="button" class="btn btn-sm btn-outline-secondary">
        <i class="fa fa-download mr-1"></i> Download All
      </button>
    </div>
    <input type="file" id="archivos_ejemplo" multiple name="archivos_ejemplo[]" style="width:100%;">
  </div>

  <!-- Items Table -->
  <div class="items-section-wrapper">
    <?php RepositorioItem::escribir_items($cotizacion_recuperada->obtener_id()); ?>
    <?php
    Conexion::abrir_conexion();
    $items_exists = RepositorioItem::items_exists(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_id());
    $re_quote_exists = ReQuoteRepository::re_quote_exists(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_id());
    $total_services = ServiceRepository::get_total(Conexion::obtener_conexion(), $id_rfq);
    Conexion::cerrar_conexion();
    ?>
    <?php if (!$items_exists): ?>
      <div class="section-empty-state">
        <i class="fas fa-box-open"></i>
        <p>No items added yet</p>
      </div>
    <?php endif; ?>
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
  </div>

  <div class="quote-action-bar__totals">
    <div class="quote-action-total">
      <span class="quote-action-total__label">Total Price</span>
      <span class="quote-action-total__value">$<?= number_format($cotizacion_recuperada->obtener_quote_total_price(), 2); ?></span>
    </div>
    <div class="quote-action-total">
      <span class="quote-action-total__label">Total Profit</span>
      <span class="quote-action-total__value">$<?= number_format($cotizacion_recuperada->obtener_quote_profit(), 2); ?></span>
    </div>
    <div class="quote-action-total">
      <span class="quote-action-total__label">Profit %</span>
      <span class="quote-action-total__value"><?= number_format($cotizacion_recuperada->obtener_quote_profit_percentage(), 2); ?>%</span>
    </div>
  </div>

</div>
