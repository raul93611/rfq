<input type="hidden" name="id_rfq" value="<?php echo $id_rfq; ?>">
<div class="card-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="type_of_bid">Type of bid:</label>
        <select class="form-control form-control-sm" name="type_of_bid" id="type_of_bid">
          <?php
          Conexion::abrir_conexion();
          $type_of_bids = TypeOfBidRepository::get_all(Conexion::obtener_conexion());
          Conexion::cerrar_conexion();
          foreach ($type_of_bids as $key => $type_of_bid) {
          ?>
            <option <?php echo $quote->obtener_type_of_bid() == $type_of_bid->get_type_of_bid() ? 'selected' : ''; ?>><?php echo $type_of_bid->get_type_of_bid(); ?></option>
          <?php
          }
          ?>
        </select>
        <input type="hidden" name="type_of_bid_original" value="<?php echo $quote->obtener_type_of_bid(); ?>">
      </div>
      <div class="form-group">
        <label for="issue_date">Issue date:</label>
        <input type="text" class="date form-control form-control-sm" id="issue_date" name="issue_date" value="<?php echo $quote->obtener_issue_date(); ?>">
        <input type="hidden" name="issue_date_original" value="<?php echo $quote->obtener_issue_date(); ?>">
      </div>
      <div class="form-group">
        <label for="end_date">End date:</label>
        <input type="text" class="form-control form-control-sm" id="end_date" name="end_date" value="<?php echo $quote->obtener_end_date(); ?>">
        <input type="hidden" name="end_date_original" value="<?php echo $quote->obtener_end_date(); ?>">
      </div>
      <div class="form-group">
        <label for="completed_date">Completed date:</label>
        <input type="text" class="date form-control form-control-sm" id="completed_date" name="completed_date" value="<?php echo $quote->obtener_fecha_completado() ? RepositorioComment::mysql_date_to_english_format($quote->obtener_fecha_completado()) : ''; ?>">
        <input type="hidden" name="completed_date_original" value="<?php echo $quote->obtener_fecha_completado() ? RepositorioComment::mysql_date_to_english_format($quote->obtener_fecha_completado()) : ''; ?>">
      </div>
      <div class="form-group">
        <label for="expiration_date">Expiration date:</label>
        <input type="text" class="date form-control form-control-sm" id="expiration_date" name="expiration_date" value="<?php echo $quote->obtener_expiration_date() ? RepositorioComment::mysql_date_to_english_format($quote->obtener_expiration_date()) : ''; ?>">
        <input type="hidden" name="expiration_date_original" value="<?php echo $quote->obtener_expiration_date() ? RepositorioComment::mysql_date_to_english_format($quote->obtener_expiration_date()) : ''; ?>">
      </div>
      <div class="form-group">
        <label for="address">Address:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter address ..." id="address" name="address"><?php echo $quote->obtener_address(); ?></textarea>
        <input type="hidden" name="address_original" value="<?php echo $quote->obtener_address(); ?>">
      </div>
      <div class="form-group">
        <label for="ship_via">Ship via:</label>
        <select id="ship_via" class="form-control form-control-sm" name="ship_via">
          <option <?php echo $quote->obtener_ship_via() == 'GROUND' ? 'selected' : ''; ?>>GROUND</option>
          <option <?php echo $quote->obtener_ship_via() == 'BEST WAY' ? 'selected' : ''; ?>>BEST WAY</option>
        </select>
        <input type="hidden" name="ship_via_original" value="<?php echo $quote->obtener_ship_via(); ?>">
      </div>
    </div>
  </div>
</div>
<div class="card-footer footer_item">
  <a class="btn btn-primary" id="go_back" href="<?php echo EDITAR_COTIZACION . '/' . $quote->obtener_id(); ?>"><i class="fa fa-reply"></i></a>
  <button type="submit" class="btn btn-success" form="information_form" name="save_information"><i class="fa fa-check"></i> Save</button>
</div>