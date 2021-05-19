<?php
Conexion::abrir_conexion();
$fulfillment_subitem = FulfillmentSubitemRepository::get_one(Conexion::obtener_conexion(), $id_fulfillment_subitem);
$providers_list = ProviderListRepository::get_all(Conexion::obtener_conexion());
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
    </div>
  </div>
</div>
<input type="hidden" id="id_fulfillment_subitem" name="id_fulfillment_subitem" value="<?php echo $fulfillment_subitem-> get_id(); ?>">
<input type="hidden" id="id_subitem" name="id_subitem" value="<?php echo $fulfillment_subitem-> get_id_subitem(); ?>">
<div class="modal-footer">
  <button type="submit" name="save_edit_fulfillment_subitem_button" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>
