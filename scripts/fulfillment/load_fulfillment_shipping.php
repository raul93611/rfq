<?php
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
?>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="fulfillment_shipping">Description:</label>
        <input type="hidden" name="fulfillment_shipping_original" value="<?php echo $quote-> obtener_fulfillment_shipping(); ?>">
        <input type="text" class="form-control form-control-sm" id="fulfillment_shipping" name="fulfillment_shipping" value="<?php echo $quote-> obtener_fulfillment_shipping(); ?>">
      </div>
      <div class="form-group">
        <label for="amount">Amount:</label>
        <input type="hidden" name="amount_original" value="<?php echo $quote-> obtener_fulfillment_shipping_cost(); ?>">
        <input type="number" step=".01" id="amount" class="form-control form-control-sm" name="amount" value="<?php echo $quote-> obtener_fulfillment_shipping_cost(); ?>">
      </div>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="submit" name="update_fulfillment_shipping_button" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>
