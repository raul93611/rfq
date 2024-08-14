<?php
if ($cotizacion_recuperada->obtener_canal() == 'Chemonics') {
  if (!$cotizacion_recuperada->obtener_award()) {
?>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" name="award" value="si" <?php echo $cotizacion_recuperada->obtener_award() ? 'checked' : ''; ?> id="award">
      <label class="custom-control-label" for="award">Award</label>
    </div>
  <?php
  }
} else {
  if ($cotizacion_recuperada->obtener_invoice()) {
  ?>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" name="submitted_invoice" value="si" <?php echo $cotizacion_recuperada->obtener_submitted_invoice() ? 'checked' : ''; ?> id="submitted_invoice">
      <label class="custom-control-label" for="submitted_invoice">Submitted Invoice</label>
    </div>
  <?php
  } else if ($cotizacion_recuperada->isEnabledToInvoice()) {
  ?>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" name="invoice" value="si" <?php echo $cotizacion_recuperada->obtener_invoice() ? 'checked' : ''; ?> id="invoice">
      <label class="custom-control-label" for="invoice">Invoice</label>
    </div>
  <?php
  } else if ($cotizacion_recuperada->obtener_fullfillment()) {
  ?>
    <div class="text-danger">
      Fill out the required information!
    </div>
  <?php
  } else if ($cotizacion_recuperada->isEnabledToFulfillment()) {
  ?>
    <div id="id1" class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" name="fulfillment" value="si" <?php echo $cotizacion_recuperada->obtener_fullfillment() ? 'checked' : ''; ?> id="fulfillment">
      <label class="custom-control-label" for="fulfillment">Fulfillment</label>
    </div>
  <?php
  } else if ($cotizacion_recuperada->obtener_award()) {
  ?>
    <div class="text-danger">
      Fill out the required information!
    </div>
  <?php
  } else  if ($cotizacion_recuperada->obtener_status() && !$cotizacion_recuperada->obtener_award()) {
  ?>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" name="award" value="si" <?php echo $cotizacion_recuperada->obtener_award() ? 'checked' : ''; ?> id="award">
      <label class="custom-control-label" for="award">Award</label>
    </div>
  <?php
  } else if ($cotizacion_recuperada->obtener_completado() && !$cotizacion_recuperada->obtener_status()) {
  ?>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" name="status" value="si" <?php echo $cotizacion_recuperada->obtener_status() ? 'checked' : ''; ?> id="status">
      <label class="custom-control-label" for="status">Submitted</label>
    </div>
  <?php
  } else if (!$cotizacion_recuperada->obtener_completado()) {
  ?>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" name="completado" value="si" <?php echo $cotizacion_recuperada->obtener_completado() ? 'checked' : ''; ?> id="completado">
      <label class="custom-control-label" for="completado">Completed</label>
    </div>
<?php
  }
}
?>