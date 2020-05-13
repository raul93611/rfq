<input type="hidden" name="id_rfq" value="<?php echo $cotizacion_recuperada->obtener_id(); ?>">
<div class="card-body">
  <div class="row">
  <?php
  if($cotizacion_recuperada-> obtener_award()){
    ?>
      <div class="col-md-12">
        <div class="form-group">
          <label for="contract_number">Contract number:</label>
          <input type="text" class="form-control form-control-sm" name="contract_number" value="<?php echo $cotizacion_recuperada-> obtener_contract_number(); ?>">
        </div>
      </div>
    <?php
  }
  ?>
  <div class="col-md-12">
    <div class="form-group">
      <label for="email_code">Code:</label>
      <input type="text" class="form-control form-control-sm" id="email_code" name="email_code" value="<?php echo $cotizacion_recuperada->obtener_email_code(); ?>">
      <input type="hidden" name="email_code_original" value="<?php echo $cotizacion_recuperada-> obtener_email_code(); ?>">
    </div>
  </div>
  <div class="col-md-12">
    <div class="form-group">
      <label for="type_of_bid">Type of bid:</label>
      <select class="form-control form-control-sm" name="type_of_bid" id="type_of_bid">
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Audio Visual'){echo 'selected';} ?>>Audio Visual</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Back up Batteries'){echo 'selected';} ?>>Back up Batteries</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Cameras'){echo 'selected';} ?>>Cameras</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Computer Peripherals'){echo 'selected';} ?>>Computer Peripherals</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Computers'){echo 'selected';} ?>>Computers</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Medical'){echo 'selected';} ?>>Medical</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Miscellaneous'){echo 'selected';} ?>>Miscellaneous</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Monitors & Televisions'){echo 'selected';} ?>>Monitors & Televisions</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Office Supplies'){echo 'selected';} ?>>Office Supplies</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Peripherals'){echo 'selected';} ?>>Peripherals</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Portable Radios'){echo 'selected';} ?>>Portable Radios</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Printers'){echo 'selected';} ?>>Printers</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Servers'){echo 'selected';} ?>>Servers</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Software'){echo 'selected';} ?>>Software</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Tactical'){echo 'selected';} ?>>Tactical</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Tools'){echo 'selected';} ?>>Tools</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Scanners'){echo 'selected';} ?>>Scanners</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Projectors'){echo 'selected';} ?>>Projectors</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Video Cameras'){echo 'selected';} ?>>Video Cameras</option>
        <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Phones'){echo 'selected';} ?>>Phones</option>
      </select>
    </div>
  </div>
  <div class="col-md-12">
    <div class="form-group">
      <label for="issue_date">Issue date:</label>
      <input type="text" class="form-control form-control-sm" id="issue_date" name="issue_date" value="<?php echo $cotizacion_recuperada->obtener_issue_date(); ?>">
    </div>
  </div>
  <div class="col-md-12">
    <div class="form-group">
      <label for="end_date">End date:</label>
      <input type="text" class="form-control form-control-sm" id="end_date" name="end_date" value="<?php echo $cotizacion_recuperada->obtener_end_date(); ?>">
    </div>
  </div>
  <div class="col-md-12">
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
      </select>
    </div>
  </div>
  <div class="col-md-12">
    <?php
    Input::print_designated_user($cotizacion_recuperada);
    ?>
  </div>
    <?php
    if($cotizacion_recuperada-> obtener_rfp()){
      ?>
      <div class="col-md-12">
        <div class="form-group">
          <label for="rfp">Proposal RFP:</label>
          <input type="text" class="form-control form-control-sm" disabled value="<?php echo $cotizacion_recuperada-> obtener_rfp(); ?>">
        </div>
      </div>
      <?php
    }
    ?>
  </div>
  <div class="col-md-12">
    <?php
    $ruta = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $cotizacion_recuperada->obtener_id();
    Input::print_input_file($ruta);
    ?>
  </div>
  <br>
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
  ?>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
          <label for="completed_date">Completed date:</label>
          <input type="text" class="form-control form-control-sm" id="completed_date" name="completed_date" value="<?php if($cotizacion_recuperada->obtener_fecha_completado() != '0000-00-00'){echo RepositorioComment::mysql_date_to_english_format($cotizacion_recuperada->obtener_fecha_completado());} ?>">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
          <label for="expiration_date">Expiration date:</label>
          <input type="text" class="form-control form-control-sm" id="expiration_date" name="expiration_date" value="<?php if($cotizacion_recuperada->obtener_expiration_date() != '0000-00-00'){echo RepositorioComment::mysql_date_to_english_format($cotizacion_recuperada->obtener_expiration_date());} ?>">
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
      </div>
        <div class="form-group">
          <label for="ship_to">Ship to:</label>
          <textarea class="form-control form-control-sm" rows="5" placeholder="Enter ship to ..." id="ship_to" name="ship_to"><?php echo $cotizacion_recuperada->obtener_ship_to(); ?></textarea>
          <input type="hidden" name="ship_to_original" value="<?php echo $cotizacion_recuperada-> obtener_ship_to(); ?>">
        </div>
    </div>
  </div>
  <?php
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
    if ($cotizacion_recuperada->obtener_completado() && $cotizacion_recuperada->obtener_status() && !$cotizacion_recuperada->obtener_award() && $cargo < 4) {
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
    }else{
      echo '<a class="btn btn-primary" id="go_back" href="' . RFP_QUOTES . '"><i class="fa fa-reply"></i></a>';
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
    <a class="btn btn-info add_item_charter" href="<?php echo CUESTIONARIO . '/' . $cotizacion_recuperada->obtener_id(); ?>"><i class="fa fa-sticky-note"></i> Project charter</a>
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
      if(!$cotizacion_recuperada-> obtener_fullfillment() && $cotizacion_recuperada-> obtener_award() && $re_quote_exists && !$cotizacion_recuperada-> obtener_rfp()){
        ?>
        <a href="#" id="fullfillment" class="btn btn-primary"><i class="fas fa-share-square"></i> Full-fillment</a>
        <?php
      }
    }
    ?>
  </div>
