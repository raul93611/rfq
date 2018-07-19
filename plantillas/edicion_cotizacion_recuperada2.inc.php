<input type="hidden" name="id_rfq" value="<?php echo $cotizacion_recuperada->obtener_id(); ?>">
<!--<div class="card-body">-->
    <div class="form-group">
        <label for="comments">Comments:</label>
        <select id="comments" class="form-control" name="comments">
            <option <?php if ($cotizacion_recuperada->obtener_comments() == 'No comments') {echo 'selected';} ?>>No comments</option>
            <option <?php if ($cotizacion_recuperada->obtener_comments() == 'No Bid') { echo 'selected';} ?>>No Bid</option>
            <option <?php if ($cotizacion_recuperada->obtener_comments() == 'Manufacturer in the Bid') {echo 'selected';} ?>>Manufacturer in the Bid</option>
            <option <?php if ($cotizacion_recuperada->obtener_comments() == 'Expired due date') { echo 'selected';} ?>>Expired due date</option>
            <option <?php if ($cotizacion_recuperada->obtener_comments() == 'Supplier did not provide a quote') { echo 'selected';} ?>>Supplier did not provide a quote</option>
            <option <?php if($cotizacion_recuperada->obtener_comments() == 'Others'){echo 'selected';} ?>>Others</option>
            <option <?php if ($cotizacion_recuperada->obtener_comments() == 'Working on it'){echo 'selected';} ?>>Working on it</option>
        </select>
    </div>
    <div class="form-group">
        <label for="ship_via">Ship via:</label>
        <select id="ship_via" class="form-control" name="ship_via">
            <option <?php if ($cotizacion_recuperada->obtener_ship_via() == 'GROUND') { echo 'selected';} ?>>GROUND</option>
            <option <?php if ($cotizacion_recuperada->obtener_ship_via() == 'BEST WAY') { echo 'selected';} ?>>BEST WAY</option>
        </select>
    </div>
    <div class="row">
        <div class="col">

            <div class="form-group">
                <label for="address">Address:</label>
                <textarea class="form-control" rows="5" placeholder="Enter address ..." id="address" name="address"><?php echo $cotizacion_recuperada->obtener_address(); ?></textarea>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="ship_to">Ship to:</label>
                <textarea class="form-control" rows="5" placeholder="Enter ship to ..." id="ship_to" name="ship_to"><?php echo $cotizacion_recuperada->obtener_ship_to(); ?></textarea>
            </div>
        </div>
    </div>
<?php
if ($cotizacion_recuperada->obtener_completado() && $cotizacion_recuperada->obtener_status() && !$cotizacion_recuperada->obtener_award() && $cargo < 4) {
    ?>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="award" value="si" <?php if ($cotizacion_recuperada->obtener_award()) { echo 'checked'; } ?> id="award">
            <label class="form-check-label" for="award">Award</label>
        </div>

        <?php
    } else if ($cotizacion_recuperada->obtener_completado() && !$cotizacion_recuperada->obtener_status() && !$cotizacion_recuperada->obtener_award() && $cargo < 4) {
        ?>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="status" value="si" <?php if ($cotizacion_recuperada->obtener_status()) { echo 'checked'; } ?> id="status">
            <label class="form-check-label" for="status">Submit</label>
        </div>
        <?php
    } else if (!$cotizacion_recuperada->obtener_completado() && !$cotizacion_recuperada->obtener_status() && !$cotizacion_recuperada->obtener_award()) {
        ?>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="completado" value="si" <?php if ($cotizacion_recuperada->obtener_completado()) { echo 'checked';} ?> id="completado">
            <label class="form-check-label" for="completado">Completed</label>
        </div>
    <?php
}
?>
</div>
<div class="card-footer footer_item">
  <?php
    if(count($items)){
      ?>
      <button type="button" id="calculate" class="btn btn-info"><i class="fa fa-calculator"></i> Calculate</button>
      <?php
    }
  ?>
    <button type="submit" class="btn btn-success" name="guardar_cambios_cotizacion"><i class="fa fa-check"></i> Save</button>
<?php
if ($cotizacion_recuperada->obtener_award()) {
    echo '<a class="btn btn-primary" href="' . AWARD . $canal . '"><i class="fa fa-backward"></i> Go back</a>';
} else if ($cotizacion_recuperada->obtener_status()) {
    echo '<a class="btn btn-primary" href="' . SUBMITTED . $canal . '"><i class="fa fa-backward"></i> Go back</a>';
} else if ($cotizacion_recuperada->obtener_completado()) {
    echo '<a class="btn btn-primary" href="' . COMPLETADOS . $canal . '"><i class="fa fa-backward"></i> Go back</a>';
} else if ($cotizacion_recuperada->obtener_comments() != 'No comments' && $cotizacion_recuperada->obtener_comments() != 'Working on it') {
    echo '<a class="btn btn-primary" href="' . NO_BID . '"><i class="fa fa-backward"></i> Go back</a>';
}else{
  echo '<a class="btn btn-primary" href="' . COTIZACIONES . $canal . '"><i class="fa fa-backward"></i> Go back</a>';
}
?>
    <a class="btn btn-primary" href="<?php echo CUESTIONARIO . '/' . $cotizacion_recuperada->obtener_id(); ?>"><i class="fa fa-sticky-note"></i> Project charter</a>
    <!--<button type="submit" class="btn btn-success" name="guardar_cambios_cotizacion"><i class="fa fa-save"></i> Save</button>-->
    <a class="btn btn-primary" href="<?php echo ADD_ITEM . '/' . $cotizacion_recuperada->obtener_id(); ?>"><i class="fa fa-plus-circle"></i> Add item</a>
</div>
<!--
<br>



-->
