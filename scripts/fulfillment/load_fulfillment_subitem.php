<?php
Conexion::abrir_conexion();
$fulfillment_subitem = FulfillmentSubitemRepository::get_one(Conexion::obtener_conexion(), $id_fulfillment_subitem);
$providers_list = ProviderListRepository::get_all(Conexion::obtener_conexion());
$payment_terms = PaymentTermRepository::get_all(Conexion::obtener_conexion());
Conexion::cerrar_conexion();
?>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label>Provider:</label>
        <select name="provider" class="custom-select">
          <?php
          foreach ($providers_list as $key => $provider) {
            ?>
            <option <?php echo $provider-> get_company_name() == $fulfillment_subitem-> get_provider() ? 'selected' : ''; ?>><?php echo $provider-> get_company_name(); ?></option>
            <?php
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" class="form-control form-control-sm" name="quantity" value="<?php echo $fulfillment_subitem-> get_quantity(); ?>">
      </div>
      <div class="form-group">
        <label for="unit_cost">Unit Cost:</label>
        <input type="number" step=".01" id="unit_cost" class="form-control form-control-sm" name="unit_cost" value="<?php echo $fulfillment_subitem-> get_unit_cost(); ?>">
      </div>
      <div class="form-group">
        <label for="other_cost">Other Cost:</label>
        <input type="number" step=".01" id="other_cost" class="form-control form-control-sm" name="other_cost" value="<?php echo $fulfillment_subitem-> get_other_cost(); ?>">
      </div>
      <div class="form-group">
        <label for="payment_term">Payment Term:</label>
        <select class="custom-select" name="payment_term" id="payment_term">
          <?php
            foreach ($payment_terms as $key => $payment_term) {
              ?>
              <option <?php echo $payment_term-> get_payment_term() == $fulfillment_subitem-> get_payment_term() ? 'selected' : ''; ?>><?php echo $payment_term-> get_payment_term(); ?></option>
              <?php
            }
          ?>
        </select>
      </div>
      <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="net30_cc" name="net30_cc" value="1" <?php echo $fulfillment_subitem-> get_net30_cc() ? 'checked' : ''; ?>>
        <label class="custom-control-label" for="net30_cc">Net 30/CC</label><br>
        <span class="text-muted">If checked a 2.99% will be apply to the result.</span>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="id_fulfillment_subitem" name="id_fulfillment_subitem" value="<?php echo $fulfillment_subitem-> get_id(); ?>">
<input type="hidden" id="id_subitem" name="id_subitem" value="<?php echo $fulfillment_subitem-> get_id_subitem(); ?>">
<div class="modal-footer">
  <button type="submit" name="save_edit_fulfillment_subitem_button" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>
