<input type="hidden" name="id_rfq" value="<?php echo $cotizacion_recuperada->obtener_id(); ?>">
<?php
if($cotizacion_recuperada-> obtener_canal() == 'FedBid'){
  ?>
  <div class="row">
    <div class="col-md-6">
      <label for="total_cost_fedbid">Total cost:</label>
      <input type="number" step=".01" name="total_cost_fedbid" class="form-control form-control-sm" value="<?php echo $cotizacion_recuperada-> obtener_total_cost(); ?>">
    </div>
    <div class="col-md-6">
      <label for="total_price_fedbid">Total price:</label>
      <input type="number" step=".01" name="total_price_fedbid" class="form-control form-control-sm" value="<?php echo $cotizacion_recuperada-> obtener_total_price(); ?>">
    </div>
  </div>
  <br>
  <?php
}
if($cotizacion_recuperada-> obtener_canal() == 'Chemonics'){
  ?>
  <div class="row">
    <div class="col-md-12">
      <label for="total_price_chemonics">Total price:</label>
      <input type="number" step=".01" name="total_price_chemonics" class="form-control form-control-sm" value="<?php echo $cotizacion_recuperada-> obtener_total_price(); ?>">
    </div>
  </div>
  <br>
  <?php
}
?>
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
        <label for="completed_date">Completed date:</label>
        <input type="text" class="form-control form-control-sm" id="completed_date" name="completed_date"
        <?php
        $hoy = getdate();
        $fecha_default = $hoy['mon'] . '/' . $hoy['mday'] . '/' . $hoy['year'];
        if($cotizacion_recuperada->obtener_fecha_completado() != '0000-00-00'){
          $fecha_completado_formato = date('m/d/Y', strtotime($cotizacion_recuperada->obtener_fecha_completado()));
          echo 'value="' . $fecha_completado_formato . '"';
        }else{

          echo 'value="' . $fecha_default . '"';
        }
        ?>>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
        <label for="expiration_date">Expiration date:</label>
        <input type="text" class="form-control form-control-sm" id="expiration_date" name="expiration_date"
        <?php
        if($cotizacion_recuperada->obtener_expiration_date() != '0000-00-00'){
          $expiration_date_formato = date('m/d/Y', strtotime($cotizacion_recuperada->obtener_expiration_date()));
          echo 'value="' . $expiration_date_formato . '"';
        }else{
          echo 'value="' . $fecha_default . '"';
        }
        ?>>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label for="comments">Comments:</label>
      <select id="comments" class="form-control form-control-sm" name="comments">
        <option <?php if ($cotizacion_recuperada->obtener_comments() == 'No comments') {echo 'selected';} ?>>No comments</option>
        <?php
        if($cargo < 4 && $cotizacion_recuperada-> obtener_award()){
          ?>
          <option <?php if ($cotizacion_recuperada->obtener_comments() == 'QuickBooks') { echo 'selected';} ?>>QuickBooks</option>
          <?php
        }
        if($cargo < 4){
          ?>
          <option <?php if ($cotizacion_recuperada->obtener_comments() == 'No Bid') { echo 'selected';} ?>>No Bid</option>
          <option <?php if ($cotizacion_recuperada->obtener_comments() == 'Manufacturer in the Bid') {echo 'selected';} ?>>Manufacturer in the Bid</option>
          <option <?php if ($cotizacion_recuperada->obtener_comments() == 'Expired due date') { echo 'selected';} ?>>Expired due date</option>
          <option <?php if ($cotizacion_recuperada->obtener_comments() == 'Supplier did not provide a quote') { echo 'selected';} ?>>Supplier did not provide a quote</option>
          <option <?php if($cotizacion_recuperada->obtener_comments() == 'Others'){echo 'selected';} ?>>Others</option>
          <option <?php if($cotizacion_recuperada-> obtener_comments() == 'Not submitted'){echo 'selected';} ?>>Not submitted</option>
          <?php
        }
        ?>
        <option <?php if($cotizacion_recuperada-> obtener_comments() == 'Cancelled'){ echo 'selected'; } ?>>Cancelled</option>
        <option <?php if ($cotizacion_recuperada-> obtener_comments() == 'Working on it'){echo 'selected';} ?>>Working on it</option>
      </select>
      <input type="hidden" name="comments_original" value="<?php echo $cotizacion_recuperada-> obtener_comments(); ?>">
    </div>
      <div class="form-group">
        <label for="address">Address:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter address ..." id="address" name="address"><?php echo $cotizacion_recuperada->obtener_address(); ?></textarea>
        <input type="hidden" name="address_original" value="<?php echo $cotizacion_recuperada-> obtener_address(); ?>">
      </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="ship_via">Ship via:</label>
      <select id="ship_via" class="form-control form-control-sm" name="ship_via">
        <option <?php if ($cotizacion_recuperada->obtener_ship_via() == 'GROUND') { echo 'selected';} ?>>GROUND</option>
        <option <?php if ($cotizacion_recuperada->obtener_ship_via() == 'BEST WAY') { echo 'selected';} ?>>BEST WAY</option>
      </select>
    </div>
      <div class="form-group">
        <label for="ship_to">Ship to:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter ship to ..." id="ship_to" name="ship_to"><?php echo $cotizacion_recuperada->obtener_ship_to(); ?></textarea>
        <input type="hidden" name="ship_to_original" value="<?php echo $cotizacion_recuperada-> obtener_ship_to(); ?>">
      </div>
  </div>
</div>
<?php
if($cotizacion_recuperada-> obtener_canal() == 'Chemonics'){
  if(!$cotizacion_recuperada->obtener_award()){
    ?>
    <div class="form-check">
      <input type="checkbox" class="form-check-input" name="award" value="si" <?php if ($cotizacion_recuperada->obtener_award()) { echo 'checked'; } ?> id="award">
      <label class="form-check-label" for="award">Award</label>
    </div>
    <?php
  }
}else{
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
        <label class="form-check-label" for="status">Submitted</label>
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
}
?>
</div>
<div class="card-footer footer_item" id="footer_lg">
  <?php
  if ($cotizacion_recuperada->obtener_award() && ($cotizacion_recuperada->obtener_comments() == 'No comments' || $cotizacion_recuperada->obtener_comments() == 'Working on it' || $cotizacion_recuperada-> obtener_comments() == 'QuickBooks')) {
    echo '<a class="btn btn-primary" id="go_back" href="' . AWARD . $canal . '"><i class="fa fa-reply"></i></a>';
  } else if ($cotizacion_recuperada->obtener_status() && ($cotizacion_recuperada->obtener_comments() == 'No comments' || $cotizacion_recuperada->obtener_comments() == 'Working on it' || $cotizacion_recuperada-> obtener_comments() == 'QuickBooks')) {
    echo '<a class="btn btn-primary" id="go_back" href="' . SUBMITTED . $canal . '"><i class="fa fa-reply"></i></a>';
  } else if ($cotizacion_recuperada->obtener_completado() && ($cotizacion_recuperada->obtener_comments() == 'No comments' || $cotizacion_recuperada->obtener_comments() == 'Working on it' || $cotizacion_recuperada-> obtener_comments() == 'QuickBooks')) {
    echo '<a class="btn btn-primary" id="go_back" href="' . COMPLETADOS . $canal . '"><i class="fa fa-reply"></i></a>';
  } else if ($cotizacion_recuperada->obtener_comments() == 'No Bid' || $cotizacion_recuperada->obtener_comments() == 'Manufacturer in the Bid' || $cotizacion_recuperada->obtener_comments() == 'Expired due date' || $cotizacion_recuperada->obtener_comments() == 'Supplier did not provide a quote' || $cotizacion_recuperada->obtener_comments() == 'Others') {
    echo '<a class="btn btn-primary" id="go_back" href="' . NO_BID . '"><i class="fa fa-reply"></i></a>';
  }else if($cotizacion_recuperada-> obtener_comments() == 'No submitted'){
    echo '<a class="btn btn-primary" id="go_back" href="' . NO_SUBMITTED . '"><i class="fa fa-reply"></i></a>';
  }else if(!empty($cotizacion_recuperada-> obtener_canal())){
    echo '<a class="btn btn-primary" id="go_back" href="' . COTIZACIONES . $canal . '"><i class="fa fa-reply"></i></a>';
  }else{
    echo '<a class="btn btn-primary" id="go_back" href="' . RFP_QUOTES . '"><i class="fa fa-reply"></i></a>';
  }
  ?>
  <button type="submit" class="btn btn-success" id="save_item" name="guardar_cambios_cotizacion"><i class="fa fa-check"></i> Save</button>
  <?php
  if($cotizacion_recuperada-> obtener_canal() != 'FedBid'){
    ?>
    <a class="btn btn-primary add_item_charter" href="<?php echo ADD_ITEM . '/' . $cotizacion_recuperada->obtener_id(); ?>"><i class="fa fa-plus-circle"></i> Add item</a>
    <?php
  }
  ?>
  <a class="btn btn-info add_item_charter" href="<?php echo CUESTIONARIO . '/' . $cotizacion_recuperada->obtener_id(); ?>"><i class="fa fa-sticky-note"></i> Project charter</a>
  <a href="#" id="add_comment" class="btn btn-primary add_item_charter"><i class="fas fa-plus"></i> Add comment</a>
  <?php
  if($cotizacion_recuperada-> obtener_canal() != 'Chemonics'){
    if($cotizacion_recuperada-> obtener_award()){
      ?>
      <a href="<?php echo RE_QUOTE . $cotizacion_recuperada-> obtener_id(); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Re-quote</a>
      <?php
    }
    Conexion::abrir_conexion();
    $re_quote_exists = ReQuoteRepository::re_quote_exists(Conexion::obtener_conexion(), $cotizacion_recuperada-> obtener_id());
    Conexion::cerrar_conexion();
    if(!$cotizacion_recuperada-> obtener_fullfillment() && $cotizacion_recuperada-> obtener_award() && $re_quote_exists){
      ?>
      <a href="#" id="fullfillment" class="btn btn-primary"><i class="fas fa-share-square"></i> Full-fillment</a>
      <?php
    }
  }
  ?>
  <a href="<?php echo PDF_TABLA_ITEMS . $cotizacion_recuperada-> obtener_id(); ?>" target="_blank" class="btn btn-primary"><i class="fas fa-file"></i> PDF</a>
</div>
