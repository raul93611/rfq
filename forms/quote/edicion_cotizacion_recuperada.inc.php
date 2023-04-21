<input type="hidden" name="id_rfq" value="<?php echo $cotizacion_recuperada->obtener_id(); ?>">
<div class="card-body">
  <div class="row">
    <div class="col-md-12">
      <a href="<?php echo CHECKLIST . $cotizacion_recuperada->obtener_id(); ?>" id="" class="btn btn-primary"><i class="fas fa-clipboard-list"></i> Checklist</a>
    </div>
    <div class="col-md-12 mt-2">
      <a href="<?php echo INFORMATION . $cotizacion_recuperada->obtener_id(); ?>" id="" class="btn btn-primary"><i class="fas fa-clipboard-list"></i> Information</a>
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-md-12">
      <label>Documents:</label>
      <input type="file" id="archivos_ejemplo" multiple name="archivos_ejemplo[]">
    </div>
  </div>
  <?php
  RepositorioItem::escribir_items($cotizacion_recuperada->obtener_id());
  ?>
  <input type="hidden" name="id_rfq" value="<?php echo $cotizacion_recuperada->obtener_id(); ?>">
  <?php
  if ($cotizacion_recuperada->obtener_canal() == 'Chemonics') {
  ?>
    <div class="row">
      <div class="col-md-12">
        <label for="total_price_chemonics">Total price:</label>
        <input type="number" step=".01" name="total_price_chemonics" class="form-control form-control-sm" value="<?php echo $cotizacion_recuperada->obtener_total_price(); ?>">
      </div>
    </div>
    <br>
  <?php
  }
  if ($cotizacion_recuperada->isServices()) {
    include_once 'plantillas/services/services.inc.php';
  }
  Conexion::abrir_conexion();
  $total_services = ServiceRepository::get_total(Conexion::obtener_conexion(), $id_rfq);
  $re_quote_exists = ReQuoteRepository::re_quote_exists(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_id());
  $items_exists = RepositorioItem::items_exists(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_id());
  Conexion::cerrar_conexion();
  ?>
  <?php include_once 'forms/quote/templates/status_checkbox.inc.php'; ?>
</div>
<div class="card-footer footer_item" id="footer_lg">
  <?php include_once 'forms/quote/templates/go_back_button.inc.php'; ?>
  <button type="submit" class="btn btn-success" name="guardar_cambios_cotizacion"><i class="fa fa-check"></i> Save</button>
  <?php include_once 'forms/quote/templates/add_item.inc.php'; ?>
  <a href="#" id="add_comment" class="btn btn-primary add_item_charter"><i class="fas fa-plus"></i> Add comment</a>
  <?php include_once 'forms/quote/templates/actions_button.inc.php'; ?>
</div>