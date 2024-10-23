<input type="hidden" name="id_rfq" value="<?= htmlspecialchars($cotizacion_recuperada->obtener_id()); ?>">
<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <?php
      $fields = [
        'Contract Number' => $cotizacion_recuperada->obtener_contract_number(),
        'Code' => $cotizacion_recuperada->obtener_email_code(),
        'Channel' => $cotizacion_recuperada->print_channel(),
        'Designated User' => $usuario_designado->obtener_nombres() . ' ' . $usuario_designado->obtener_apellidos(),
        'Client' => $cotizacion_recuperada->obtener_client(),
        'Reference URL' => '<a target="_blank" href="' . htmlspecialchars($cotizacion_recuperada->getReferenceUrl()) . '">' . htmlspecialchars($cotizacion_recuperada->getReferenceUrl()) . '</a>'
      ];

      foreach ($fields as $label => $value): ?>
        <div class="row mb-2">
          <div class="col-md-4"><b><?= htmlspecialchars($label); ?>:</b></div>
          <div class="col-md-8"><?= $value; ?></div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="col-md-6">
      <div class="row mb-2">
        <div class="col-md-3"><b>Address:</b></div>
        <div class="col-md-9"><?= nl2br(htmlspecialchars($cotizacion_recuperada->obtener_address())); ?></div>
      </div>
      <div class="row mb-2">
        <div class="col-md-3"><b>Ship To:</b></div>
        <div class="col-md-9"><?= nl2br(htmlspecialchars($cotizacion_recuperada->obtener_ship_to())); ?></div>
      </div>
    </div>
  </div>

  <div class="row mb-2">
    <div class="col-md-12">
      <a href="<?= CHECKLIST . htmlspecialchars($cotizacion_recuperada->obtener_id()); ?>" class="btn btn-primary">
        <i class="fas fa-clipboard-list"></i> Checklist
      </a>
      <a href="<?= INFORMATION . htmlspecialchars($cotizacion_recuperada->obtener_id()); ?>" class="btn btn-primary">
        <i class="fas fa-clipboard-list"></i> Information
      </a>
    </div>
  </div>

  <div class="row mb-2">
    <div class="col-md-12">
      <label>Documents:</label>
      <input type="file" id="archivos_ejemplo" multiple name="archivos_ejemplo[]">
      <button id="download-all" class="mt-3 btn btn-success">
        <i class="fa fa-download"></i> Download All
      </button>
    </div>
  </div>

  <?php
  RepositorioItem::escribir_items($cotizacion_recuperada->obtener_id());
  if ($cotizacion_recuperada->obtener_canal() == 'Chemonics'): ?>
    <div class="row mb-2">
      <div class="col-md-12">
        <label for="total_price_chemonics">Total price:</label>
        <input type="number" step=".01" name="total_price_chemonics" class="form-control form-control-sm" value="<?= htmlspecialchars($cotizacion_recuperada->obtener_total_price()); ?>">
      </div>
    </div>
  <?php endif;

  if ($cotizacion_recuperada->isServices()) {
    include_once 'plantillas/services/services.inc.php';
  }

  // Open database connection
  Conexion::abrir_conexion();
  $total_services = ServiceRepository::get_total(Conexion::obtener_conexion(), $id_rfq);
  $re_quote_exists = ReQuoteRepository::re_quote_exists(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_id());
  $items_exists = RepositorioItem::items_exists(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_id());
  // Close database connection
  Conexion::cerrar_conexion();
  ?>

  <?php include_once 'forms/quote/templates/status_checkbox.inc.php'; ?>
</div>

<div class="card-footer footer_item" id="footer_lg">
  <?php include_once 'forms/quote/templates/go_back_button.inc.php'; ?>
  <button type="submit" class="btn btn-success" name="guardar_cambios_cotizacion">
    <i class="fa fa-check"></i> Save
  </button>
  <?php include_once 'forms/quote/templates/add_item.inc.php'; ?>
  <a href="#" id="add_comment" class="btn btn-primary add_item_charter">
    <i class="fas fa-plus"></i> Add comment
  </a>
  <?php include_once 'forms/quote/templates/rooms_button.inc.php'; ?>
  <?php include_once 'forms/quote/templates/actions_button.inc.php'; ?>
</div>