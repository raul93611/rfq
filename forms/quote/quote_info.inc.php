<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label for="contract_number">Contract number:</label>
      <input type="text" class="form-control form-control-sm" name="contract_number" value="<?php echo $cotizacion_recuperada-> obtener_contract_number(); ?>">
      <input type="hidden" name="contract_number_original" value="<?php echo $cotizacion_recuperada-> obtener_contract_number(); ?>">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="email_code">Code:</label>
      <input type="text" class="form-control form-control-sm" id="email_code" name="email_code" value="<?php echo $cotizacion_recuperada->obtener_email_code(); ?>">
      <input type="hidden" name="email_code_original" value="<?php echo $cotizacion_recuperada-> obtener_email_code(); ?>">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="type_of_bid">Type of bid:</label>
      <select class="form-control form-control-sm" name="type_of_bid" id="type_of_bid">
        <?php
        Conexion::abrir_conexion();
        $type_of_bids = TypeOfBidRepository::get_all(Conexion::obtener_conexion());
        Conexion::cerrar_conexion();
        foreach ($type_of_bids as $key => $type_of_bid) {
          ?>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == $type_of_bid-> get_type_of_bid()){echo 'selected';} ?>><?php echo $type_of_bid-> get_type_of_bid(); ?></option>
          <?php
        }
        ?>
      </select>
      <input type="hidden" name="type_of_bid_original" value="<?php echo $cotizacion_recuperada-> obtener_type_of_bid(); ?>">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="issue_date">Issue date:</label>
      <input type="text" class="date form-control form-control-sm" id="issue_date" name="issue_date" value="<?php echo $cotizacion_recuperada->obtener_issue_date(); ?>">
      <input type="hidden" name="issue_date_original" value="<?php echo $cotizacion_recuperada->obtener_issue_date(); ?>">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="end_date">End date:</label>
      <input type="text" class="form-control form-control-sm" id="end_date" name="end_date" value="<?php echo $cotizacion_recuperada->obtener_end_date(); ?>">
      <input type="hidden" name="end_date_original" value="<?php echo $cotizacion_recuperada->obtener_end_date(); ?>">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="canal">Channel:</label>
      <select class="form-control form-control-sm" name="canal" id="canal">
        <option <?php if($cotizacion_recuperada-> obtener_canal() == 'GSA-Buy'){echo 'selected';} ?>>GSA-Buy</option>
        <option <?php if($cotizacion_recuperada-> obtener_canal() == 'FedBid'){echo 'selected';} ?>>FedBid</option>
        <option <?php if($cotizacion_recuperada-> obtener_canal() == 'E-mails'){echo 'selected';} ?>>E-mails</option>
        <option <?php if($cotizacion_recuperada-> obtener_canal() == 'Mailbox'){echo 'selected';} ?>>Mailbox</option>
        <option <?php if($cotizacion_recuperada-> obtener_canal() == 'FindFRP'){echo 'selected';} ?>>FindFRP</option>
        <option <?php if($cotizacion_recuperada-> obtener_canal() == 'Embassies'){echo 'selected';} ?>>Embassies</option>
        <option <?php if($cotizacion_recuperada-> obtener_canal() == 'FBO'){echo 'selected';} ?>>FBO</option>
        <option <?php if($cotizacion_recuperada-> obtener_canal() == 'Chemonics'){echo 'selected';} ?>>Chemonics</option>
        <option <?php if($cotizacion_recuperada-> obtener_canal() == 'Ebay & Amazon'){echo 'selected';} ?>>Ebay & Amazon</option>
        <option <?php if($cotizacion_recuperada-> obtener_canal() == 'Stars III'){echo 'selected';} ?>>Stars III</option>
      </select>
      <input type="hidden" name="canal_original" value="<?php echo $cotizacion_recuperada-> obtener_canal(); ?>">
    </div>
  </div>
  <div class="col-md-12">
    <?php
    Input::print_designated_user($cotizacion_recuperada);
    ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
        <label for="completed_date">Completed date:</label>
        <input type="text" class="date form-control form-control-sm" id="completed_date" name="completed_date" value="<?php if($cotizacion_recuperada->obtener_fecha_completado() != '0000-00-00'){echo RepositorioComment::mysql_date_to_english_format($cotizacion_recuperada->obtener_fecha_completado());} ?>">
        <input type="hidden" name="completed_date_original" value="<?php if($cotizacion_recuperada->obtener_fecha_completado() != '0000-00-00'){echo RepositorioComment::mysql_date_to_english_format($cotizacion_recuperada->obtener_fecha_completado());} ?>">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
        <label for="expiration_date">Expiration date:</label>
        <input type="text" class="date form-control form-control-sm" id="expiration_date" name="expiration_date" value="<?php if($cotizacion_recuperada->obtener_expiration_date() != '0000-00-00'){echo RepositorioComment::mysql_date_to_english_format($cotizacion_recuperada->obtener_expiration_date());} ?>">
        <input type="hidden" name="expiration_date_original" value="<?php if($cotizacion_recuperada->obtener_expiration_date() != '0000-00-00'){echo RepositorioComment::mysql_date_to_english_format($cotizacion_recuperada->obtener_expiration_date());} ?>">
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label for="comments">Comments:</label>
      <select id="comments" class="form-control form-control-sm" name="comments">
        <option <?php if ($cotizacion_recuperada->obtener_comments() == 'No comments') {echo 'selected';} ?>>No comments</option>
        <option <?php if ($cotizacion_recuperada->obtener_comments() == 'QuickBooks') { echo 'selected';} ?>>QuickBooks</option>
        <option <?php if ($cotizacion_recuperada->obtener_comments() == 'No Bid') { echo 'selected';} ?>>No Bid</option>
        <option <?php if ($cotizacion_recuperada->obtener_comments() == 'Manufacturer in the Bid') {echo 'selected';} ?>>Manufacturer in the Bid</option>
        <option <?php if ($cotizacion_recuperada->obtener_comments() == 'Expired due date') { echo 'selected';} ?>>Expired due date</option>
        <option <?php if ($cotizacion_recuperada->obtener_comments() == 'Supplier did not provide a quote') { echo 'selected';} ?>>Supplier did not provide a quote</option>
        <option <?php if($cotizacion_recuperada->obtener_comments() == 'Others'){echo 'selected';} ?>>Others</option>
        <option <?php if($cotizacion_recuperada-> obtener_comments() == 'Not submitted'){echo 'selected';} ?>>Not submitted</option>
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
      <input type="hidden" name="ship_via_original" value="<?php echo $cotizacion_recuperada->obtener_ship_via(); ?>">
    </div>
      <div class="form-group">
        <label for="ship_to">Ship to:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter ship to ..." id="ship_to" name="ship_to"><?php echo $cotizacion_recuperada->obtener_ship_to(); ?></textarea>
        <input type="hidden" name="ship_to_original" value="<?php echo $cotizacion_recuperada-> obtener_ship_to(); ?>">
      </div>
  </div>
</div>
