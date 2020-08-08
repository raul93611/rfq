<input type="hidden" name="id_rfq" value="<?php echo $cotizacion_recuperada->obtener_id(); ?>">
<div class="card-body">
  <div class="row">
    <div class="col-md-4">
      <h6><b>Contract Number:</b> <small><?php echo $cotizacion_recuperada-> obtener_contract_number(); ?></small></h6>
      <h6><b>Code:</b> <small><?php echo $cotizacion_recuperada-> obtener_email_code(); ?></small></h6>
      <h6><b>Type of Bid:</b> <small><?php echo $cotizacion_recuperada-> obtener_type_of_bid() ; ?></small></h6>
      <h6><b>Issue Date:</b> <small><?php echo $cotizacion_recuperada->obtener_issue_date(); ?></small></h6>
      <h6><b>End Date:</b> <small><?php echo $cotizacion_recuperada->obtener_end_date(); ?></small></h6>
      <h6><b>Channel:</b> <small><?php echo $cotizacion_recuperada-> obtener_canal() ?></small></h6>
      <h6><b>Designated User:</b> <small><?php echo Input::get_designated_user($cotizacion_recuperada); ?></small></h6>
      <h6><b>Completed Date:</b> <small><?php if($cotizacion_recuperada->obtener_fecha_completado() != '0000-00-00'){echo RepositorioComment::mysql_date_to_english_format($cotizacion_recuperada->obtener_fecha_completado());} ?></small></h6>
      <h6><b>Expiration Date:</b> <small><?php if($cotizacion_recuperada->obtener_expiration_date() != '0000-00-00'){echo RepositorioComment::mysql_date_to_english_format($cotizacion_recuperada->obtener_expiration_date());} ?></small></h6>
      <h6><b>Comments:</b> <small><?php echo $cotizacion_recuperada->obtener_comments(); ?></small></h6>
      <h6><b>Ship Via:</b> <small><?php echo $cotizacion_recuperada->obtener_ship_via(); ?></small></h6>
    </div>
    <div class="col-md-4">
      <h6><b>Address:</b></h6>
      <p><?php echo nl2br($cotizacion_recuperada->obtener_address()); ?></p>
    </div>
    <div class="col-md-4">
      <h6><b>Ship To:</b></h6>
      <p><?php echo nl2br($cotizacion_recuperada-> obtener_ship_to()); ?></p>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <button type="button" id="quote_info_button" class="float-right btn btn-primary" name="button">Edit</button>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <?php
      $ruta = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $cotizacion_recuperada->obtener_id();
      Input::print_input_file($ruta);
      ?>
    </div>
  </div>
  <?php
  RepositorioItem::escribir_items($cotizacion_recuperada->obtener_id());
  ?>
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
  if($cotizacion_recuperada-> obtener_canal() == 'Chemonics' || $cotizacion_recuperada-> obtener_canal() == 'Ebay & Amazon'){
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
  if($cotizacion_recuperada-> obtener_type_of_bid() == 'Services'){
    include_once 'plantillas/services.inc.php';
  }
  if($cotizacion_recuperada-> obtener_canal() == 'Chemonics' || $cotizacion_recuperada-> obtener_canal() == 'Ebay & Amazon'){
    if(!$cotizacion_recuperada->obtener_award()){
      ?>
      <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="award" value="si" <?php if ($cotizacion_recuperada->obtener_award()) { echo 'checked'; } ?> id="award">
        <label class="custom-control-label" for="award">Award</label>
      </div>
      <?php
    }
  }else{
    if ($cotizacion_recuperada->obtener_completado() && $cotizacion_recuperada->obtener_status() && !$cotizacion_recuperada->obtener_award() && $_SESSION['cargo'] < 4) {
      ?>
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" name="award" value="si" <?php if ($cotizacion_recuperada->obtener_award()) { echo 'checked'; } ?> id="award">
          <label class="custom-control-label" for="award">Award</label>
        </div>

        <?php
      } else if ($cotizacion_recuperada->obtener_completado() && !$cotizacion_recuperada->obtener_status() && !$cotizacion_recuperada->obtener_award()) {
        ?>
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" name="status" value="si" <?php if ($cotizacion_recuperada->obtener_status()) { echo 'checked'; } ?> id="status">
          <label class="custom-control-label" for="status">Submitted</label>
        </div>
        <?php
      } else if (!$cotizacion_recuperada->obtener_completado() && !$cotizacion_recuperada->obtener_status() && !$cotizacion_recuperada->obtener_award()) {
        ?>
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" name="completado" value="si" <?php if ($cotizacion_recuperada->obtener_completado()) { echo 'checked';} ?> id="completado">
          <label class="custom-control-label" for="completado">Completed</label>
        </div>
      <?php
    }
  }
  ?>
  </div>
  <div class="card-footer footer_item" id="footer_lg">
    <?php
    $canal = Input::translate_channel($cotizacion_recuperada-> obtener_canal());
    if($cotizacion_recuperada-> obtener_fullfillment()){
      echo '<a class="btn btn-primary" id="go_back" href="' . FULFILLMENT_QUOTES . '"><i class="fa fa-reply"></i></a>';
    }else if ($cotizacion_recuperada->obtener_award() && ($cotizacion_recuperada->obtener_comments() == 'No comments' || $cotizacion_recuperada->obtener_comments() == 'Working on it' || $cotizacion_recuperada-> obtener_comments() == 'QuickBooks')) {
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
    }
    ?>
    <button type="submit" class="btn btn-success" id="save_item" name="guardar_cambios_cotizacion"><i class="fa fa-check"></i> Save</button>
    <?php
    if($cotizacion_recuperada-> obtener_canal() != 'FedBid' && $cotizacion_recuperada-> obtener_canal() != 'Chemonics' && $cotizacion_recuperada-> obtener_canal() != 'Ebay & Amazon'){
      ?>
      <a class="btn btn-primary add_item_charter" href="<?php echo ADD_ITEM . '/' . $cotizacion_recuperada->obtener_id(); ?>"><i class="fa fa-plus-circle"></i> Add item</a>
      <?php
    }
    ?>
    <a href="#" id="add_comment" class="btn btn-primary add_item_charter"><i class="fas fa-plus"></i> Add comment</a>
    <?php
    if($cotizacion_recuperada-> obtener_canal() != 'Chemonics' && $cotizacion_recuperada-> obtener_canal() != 'Ebay & Amazon'){
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
  </div>
