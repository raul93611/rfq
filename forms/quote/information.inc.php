<input type="hidden" name="id_rfq" value="<?= $id_rfq; ?>">
<div class="card-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="email_code">Code:</label>
        <input type="text" class="form-control form-control-sm" id="email_code" name="email_code" value="<?= $quote->obtener_email_code(); ?>">
        <input type="hidden" name="email_code_original" value="<?= $quote->obtener_email_code(); ?>">
      </div>
      <div class="form-group">
        <label for="canal">Channel:</label>
        <select class="form-control form-control-sm" name="canal" id="canal">
          <option <?= $quote->obtener_canal() == 'GSA-Buy' ? 'selected' : ''; ?>>GSA-Buy</option>
          <option value="FedBid" <?= $quote->obtener_canal() == 'FedBid' ? 'selected' : ''; ?>>Unison</option>
          <option <?= $quote->obtener_canal() == 'E-mails' ? 'selected' : ''; ?>>E-mails</option>
          <option <?= $quote->obtener_canal() == 'Embassies' ? 'selected' : ''; ?>>Embassies</option>
          <option value="FBO" <?= $quote->obtener_canal() == 'FBO' ? 'selected' : ''; ?>>SAM</option>
          <option <?= $quote->obtener_canal() == 'Seaport' ? 'selected' : ''; ?>>Seaport</option>
          <option <?= $quote->obtener_canal() == 'Ebay & Amazon' ? 'selected' : ''; ?>>Ebay & Amazon</option>
          <option <?= $quote->obtener_canal() == 'Stars III' ? 'selected' : ''; ?>>Stars III</option>
        </select>
        <input type="hidden" name="canal_original" value="<?= $quote->obtener_canal(); ?>">
      </div>
      <?php
      Input::print_designated_user($quote);
      ?>
      <div class="form-group">
        <label for="type_of_bid">Type of bid:</label>
        <select class="form-control form-control-sm" name="type_of_bid" id="type_of_bid">
          <?php
          Conexion::abrir_conexion();
          $type_of_bids = TypeOfBidRepository::get_all(Conexion::obtener_conexion());
          Conexion::cerrar_conexion();
          foreach ($type_of_bids as $key => $type_of_bid) {
          ?>
            <option <?= $quote->obtener_type_of_bid() == $type_of_bid->get_type_of_bid() ? 'selected' : ''; ?>><?= $type_of_bid->get_type_of_bid(); ?></option>
          <?php
          }
          ?>
        </select>
        <input type="hidden" name="type_of_bid_original" value="<?= $quote->obtener_type_of_bid(); ?>">
      </div>
      <div class="form-group">
        <label for="issue_date">Issue date:</label>
        <input type="text" class="date form-control form-control-sm" id="issue_date" name="issue_date" value="<?= $quote->obtener_issue_date(); ?>">
        <input type="hidden" name="issue_date_original" value="<?= $quote->obtener_issue_date(); ?>">
      </div>
      <div class="form-group">
        <label for="end_date">End date:</label>
        <input type="text" class="form-control form-control-sm" id="end_date" name="end_date" value="<?= $quote->obtener_end_date(); ?>">
        <input type="hidden" name="end_date_original" value="<?= $quote->obtener_end_date(); ?>">
      </div>
      <div class="form-group">
        <label for="completed_date">Completed date:</label>
        <input type="text" class="date form-control form-control-sm" id="completed_date" name="completed_date" value="<?= $quote->obtener_fecha_completado() ? RepositorioComment::mysql_date_to_english_format($quote->obtener_fecha_completado()) : ''; ?>">
        <input type="hidden" name="completed_date_original" value="<?= $quote->obtener_fecha_completado() ? RepositorioComment::mysql_date_to_english_format($quote->obtener_fecha_completado()) : ''; ?>">
      </div>
      <div class="form-group">
        <label for="expiration_date">Expiration date:</label>
        <input type="text" class="date form-control form-control-sm" id="expiration_date" name="expiration_date" value="<?= $quote->obtener_expiration_date() ? RepositorioComment::mysql_date_to_english_format($quote->obtener_expiration_date()) : ''; ?>">
        <input type="hidden" name="expiration_date_original" value="<?= $quote->obtener_expiration_date() ? RepositorioComment::mysql_date_to_english_format($quote->obtener_expiration_date()) : ''; ?>">
      </div>
      <div class="form-group">
        <label for="comments">Comments:</label>
        <select id="comments" class="form-control form-control-sm" name="comments">
          <option <?= $quote->obtener_comments() == 'No comments' ? 'selected' : ''; ?>>No comments</option>
          <option <?= $quote->obtener_comments() == 'QuickBooks' ? 'selected' : ''; ?>>QuickBooks</option>
          <option <?= $quote->obtener_comments() == 'No Bid' ? 'selected' : ''; ?>>No Bid</option>
          <option <?= $quote->obtener_comments() == 'Manufacturer in the Bid' ? 'selected' : ''; ?>>Manufacturer in the Bid</option>
          <option <?= $quote->obtener_comments() == 'Expired due date' ? 'selected' : ''; ?>>Expired due date</option>
          <option <?= $quote->obtener_comments() == 'Supplier did not provide a quote' ? 'selected' : ''; ?>>Supplier did not provide a quote</option>
          <option <?= $quote->obtener_comments() == 'Others' ? 'selected' : ''; ?>>Others</option>
          <option <?= $quote->obtener_comments() == 'Not submitted' ? 'selected' : ''; ?>>Not submitted</option>
          <option <?= $quote->obtener_comments() == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
          <option <?= $quote->obtener_comments() == 'Working on it' ? 'selected' : ''; ?>>Working on it</option>
        </select>
        <input type="hidden" name="comments_original" value="<?= $quote->obtener_comments(); ?>">
      </div>
      <div class="form-group">
        <label for="address">Address:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter address ..." id="address" name="address"><?= $quote->obtener_address(); ?></textarea>
        <input type="hidden" name="address_original" value="<?= $quote->obtener_address(); ?>">
      </div>
      <div class="form-group">
        <label for="ship_to">Ship to:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter ship to ..." id="ship_to" name="ship_to"><?= $quote->obtener_ship_to(); ?></textarea>
        <input type="hidden" name="ship_to_original" value="<?= $quote->obtener_ship_to(); ?>">
      </div>
      <div class="form-group">
        <label for="ship_via">Ship via:</label>
        <select id="ship_via" class="form-control form-control-sm" name="ship_via">
          <option <?= $quote->obtener_ship_via() == 'GROUND' ? 'selected' : ''; ?>>GROUND</option>
          <option <?= $quote->obtener_ship_via() == 'BEST WAY' ? 'selected' : ''; ?>>BEST WAY</option>
        </select>
        <input type="hidden" name="ship_via_original" value="<?= $quote->obtener_ship_via(); ?>">
      </div>
    </div>
  </div>
</div>
<div class="card-footer footer_item">
  <a class="btn btn-primary" id="go_back" href="<?= EDITAR_COTIZACION . '/' . $quote->obtener_id(); ?>"><i class="fa fa-reply"></i></a>
  <button type="submit" class="btn btn-success" form="information_form" name="save_information"><i class="fa fa-check"></i> Save</button>
</div>