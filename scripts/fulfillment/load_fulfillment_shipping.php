<?php
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
$shippings = explode('|', $quote-> obtener_fulfillment_shipping());
$costs = explode('|', $quote-> obtener_fulfillment_shipping_cost());
?>
<div class="modal-body">
  <div class="row">
    <div class="shipping_container col-md-12">
      <?php
      foreach ($shippings as $key => $shipping) {
        $cost = $costs[$key];
        ?>
        <div class="shipping<?php echo $key; ?>">
          <div class="form-group">
            <label for="fulfillment_shipping<?php echo $key; ?>">Description:</label>
            <input type="hidden" name="fulfillment_shipping_original<?php echo $key; ?>" value="<?php echo $shipping; ?>">
            <input type="text" class="form-control form-control-sm" id="fulfillment_shipping<?php echo $key; ?>" name="fulfillment_shipping<?php echo $key; ?>" value="<?php echo $shipping; ?>">
          </div>
          <div class="form-group">
            <label for="amount<?php echo $key; ?>">Amount:</label>
            <input type="hidden" name="amount_original<?php echo $key; ?>" value="<?php echo $cost; ?>">
            <input type="number" step=".01" id="amount<?php echo $key; ?>" class="form-control form-control-sm" name="amount<?php echo $key; ?>" value="<?php echo $cost; ?>">
          </div>
        </div>
        <?php
      }
      ?>
    </div>
  </div>
  <input type="hidden" name="shipping_counter" value="<?php echo count($shippings) - 1; ?>">
  <button type="button" class="add_shipping btn btn-warning" name="button"><i class="fas fa-plus"></i> Add</button>
  <button type="button" class="remove_shipping btn btn-warning" name="button"><i class="fas fa-minus"></i> Remove</button>
</div>
<div class="modal-footer">
  <button type="submit" name="update_fulfillment_shipping_button" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>
