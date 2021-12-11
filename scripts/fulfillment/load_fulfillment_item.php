<?php
Conexion::abrir_conexion();
$fulfillment_item = FulfillmentItemRepository::get_one(Conexion::obtener_conexion(), $id_fulfillment_item);
$providers_list = ProviderListRepository::get_all(Conexion::obtener_conexion());
$payment_terms = PaymentTermRepository::get_all(Conexion::obtener_conexion());
Conexion::cerrar_conexion();
?>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label>Provider:</label>
        <input type="hidden" name="provider_original" value="<?php echo $fulfillment_item-> get_provider(); ?>">
        <select name="provider" class="custom-select">
          <?php
          foreach ($providers_list as $key => $provider) {
            ?>
            <option <?php echo $provider-> get_company_name() == $fulfillment_item-> get_provider() ? 'selected' : ''; ?>><?php echo $provider-> get_company_name(); ?></option>
            <?php
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="quantity">Quantity:</label>
        <input type="hidden" name="quantity_original" value="<?php echo $fulfillment_item-> get_quantity(); ?>">
        <input type="number" id="quantity" class="form-control form-control-sm" name="quantity" value="<?php echo $fulfillment_item-> get_quantity(); ?>">
      </div>
      <div class="form-group">
        <label for="unit_cost">Unit Cost:</label>
        <input type="hidden" name="unit_cost_original" value="<?php echo $fulfillment_item-> get_unit_cost(); ?>">
        <input type="number" step=".01" id="unit_cost" class="form-control form-control-sm" name="unit_cost" value="<?php echo $fulfillment_item-> get_unit_cost(); ?>">
      </div>
      <div class="form-group">
        <label for="other_cost">Other Cost:</label>
        <input type="hidden" name="other_cost_original" value="<?php echo $fulfillment_item-> get_other_cost(); ?>">
        <input type="number" step=".01" id="other_cost" class="form-control form-control-sm" name="other_cost" value="<?php echo $fulfillment_item-> get_other_cost(); ?>">
      </div>
      <div class="form-group">
        <label for="payment_term">Payment Term:</label>
        <input type="hidden" name="payment_term_original" value="<?php echo $fulfillment_item-> get_payment_term(); ?>">
        <select class="custom-select" name="payment_term" id="payment_term">
          <?php
            foreach ($payment_terms as $key => $payment_term) {
              ?>
              <option <?php echo $payment_term-> get_payment_term() == $fulfillment_item-> get_payment_term() ? 'selected' : ''; ?>><?php echo $payment_term-> get_payment_term(); ?></option>
              <?php
            }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="comment">Comment:</label>
        <textarea id="comment" name="comment" class="form-control form-control-sm" rows="5"><?php echo $fulfillment_item-> get_comments(); ?></textarea>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="id_fulfillment_item" name="id_fulfillment_item" value="<?php echo $fulfillment_item-> get_id(); ?>">
<input type="hidden" id="id_item" name="id_item" value="<?php echo $fulfillment_item-> get_id_item(); ?>">
<div class="modal-footer">
  <button type="submit" name="save_edit_fulfillment_item_button" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>
