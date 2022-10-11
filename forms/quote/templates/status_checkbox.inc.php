<?php
if($cotizacion_recuperada-> obtener_canal() == 'Chemonics'){
  if(!$cotizacion_recuperada->obtener_award()){
    ?>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" name="award" value="si" <?php if ($cotizacion_recuperada->obtener_award()) { echo 'checked'; } ?> id="award">
      <label class="custom-control-label" for="award">Award</label>
    </div>
    <?php
  }
}else{
  if($cotizacion_recuperada-> obtener_invoice()){
    ?>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" name="submitted_invoice" value="si" <?php if ($cotizacion_recuperada->obtener_submitted_invoice()) { echo 'checked'; } ?> id="submitted_invoice">
      <label class="custom-control-label" for="submitted_invoice">Submitted Invoice</label>
    </div>
    <?php
  }else if($cotizacion_recuperada-> obtener_fullfillment() && !is_null($cotizacion_recuperada-> obtener_fulfillment_profit()) || !is_null($cotizacion_recuperada-> obtener_services_fulfillment_profit())){
    ?>
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" name="invoice" value="si" <?php if ($cotizacion_recuperada->obtener_invoice()) { echo 'checked'; } ?> id="invoice">
      <label class="custom-control-label" for="invoice">Invoice</label>
    </div>
    <?php
  }else if($cotizacion_recuperada->obtener_award() && !$cotizacion_recuperada-> obtener_fullfillment()){
    if(($items_exists && $re_quote_exists) || (!$items_exists && $cotizacion_recuperada-> isServices())){
      ?>
      <div id="id1" class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="fulfillment" value="si" <?php if ($cotizacion_recuperada->obtener_fullfillment()) { echo 'checked'; } ?> id="fulfillment">
        <label class="custom-control-label" for="fulfillment">Fulfillment</label>
      </div>
      <?php
    }
  }else if ($cotizacion_recuperada->obtener_status() && !$cotizacion_recuperada-> obtener_award()) {
    ?>
      <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="award" value="si" <?php if ($cotizacion_recuperada->obtener_award()) { echo 'checked'; } ?> id="award">
        <label class="custom-control-label" for="award">Award</label>
      </div>
      <?php
    } else if ($cotizacion_recuperada->obtener_completado() && !$cotizacion_recuperada-> obtener_status()) {
      ?>
      <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="status" value="si" <?php if ($cotizacion_recuperada->obtener_status()) { echo 'checked'; } ?> id="status">
        <label class="custom-control-label" for="status">Submitted</label>
      </div>
      <?php
    } else if(!$cotizacion_recuperada-> obtener_completado()){
      ?>
      <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="completado" value="si" <?php if ($cotizacion_recuperada->obtener_completado()) { echo 'checked';} ?> id="completado">
        <label class="custom-control-label" for="completado">Completed</label>
      </div>
    <?php
  }
}
?>
