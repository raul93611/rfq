<input type="hidden" name="id_rfq" value="<?php echo $cotizacion_recuperada->obtener_id(); ?>">
<div class="card-body">
  <div class="row">
    <div class="col-md-4">
      <h6><b>Contract Number:</b> <small><?php echo $cotizacion_recuperada-> obtener_contract_number(); ?></small></h6>
      <h6><b>Code:</b> <small><?php echo $cotizacion_recuperada-> obtener_email_code(); ?></small></h6>
      <h6><b>Type of Bid:</b> <small><?php echo $cotizacion_recuperada-> obtener_type_of_bid() ; ?></small></h6>
      <h6><b>Issue Date:</b> <small><?php echo $cotizacion_recuperada->obtener_issue_date(); ?></small></h6>
      <h6><b>End Date:</b> <small><?php echo $cotizacion_recuperada->obtener_end_date(); ?></small></h6>
      <h6><b>Channel:</b> <small><?php echo $cotizacion_recuperada-> print_channel() ?></small></h6>
      <h6><b>Designated User:</b> <small><?php echo Input::get_designated_user($cotizacion_recuperada); ?></small></h6>
      <h6><b>Completed Date:</b> <small><?php if($cotizacion_recuperada->obtener_fecha_completado() != '0000-00-00'){echo RepositorioComment::mysql_date_to_english_format($cotizacion_recuperada->obtener_fecha_completado());} ?></small></h6>
      <h6><b>Expiration Date:</b> <small><?php if($cotizacion_recuperada->obtener_expiration_date() != '0000-00-00'){echo RepositorioComment::mysql_date_to_english_format($cotizacion_recuperada->obtener_expiration_date());} ?></small></h6>
      <h6><b>Comments:</b> <small><?php echo $cotizacion_recuperada->obtener_comments(); ?></small></h6>
      <h6><b>Ship Via:</b> <small><?php echo $cotizacion_recuperada->obtener_ship_via(); ?></small></h6>
    </div>
    <div class="col-md-4">
      <h6><b>Address:</b></h6>
      <p><?php echo nl2br($cotizacion_recuperada->obtener_address()); ?></p>
      <h6><b>City:</b> <small><?php echo $cotizacion_recuperada->obtener_city(); ?></small></h6>
      <h6><b>Zip Code:</b> <small><?php echo $cotizacion_recuperada->obtener_zip_code(); ?></small></h6>
    </div>
    <div class="col-md-4">
      <h6><b>Ship To:</b></h6>
      <p><?php echo nl2br($cotizacion_recuperada-> obtener_ship_to()); ?></p>
      <h6><b>State:</b> <small><?php echo $cotizacion_recuperada->obtener_state(); ?></small></h6>
      <h6><b>Client:</b> <small><?php echo $cotizacion_recuperada->obtener_client(); ?></small></h6>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <button type="button" id="quote_info_button" class="float-right btn btn-primary" name="button">Edit</button>
    </div>
  </div>
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
  if($cotizacion_recuperada-> isServices()){
    include_once 'plantillas/services/services.inc.php';
  }
  Conexion::abrir_conexion();
  $total_services = ServiceRepository::get_total(Conexion::obtener_conexion(), $id_rfq);
  $re_quote_exists = ReQuoteRepository::re_quote_exists(Conexion::obtener_conexion(), $cotizacion_recuperada-> obtener_id());
  $items_exists = RepositorioItem::items_exists(Conexion::obtener_conexion(), $cotizacion_recuperada-> obtener_id());
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
