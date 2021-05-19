<?php
Conexion::abrir_conexion();
$tracking_subitem = TrackingSubitemRepository::get_tracking_subitem_by_id(Conexion::obtener_conexion(), $id_tracking_subitem);
Conexion::cerrar_conexion();
$delivery_date = RepositorioComment::mysql_date_to_english_format($tracking_subitem-> get_delivery_date());
?>
<input type="hidden" id="id_tracking_subitem" name="id_tracking_subitem" value="<?php echo $tracking_subitem-> get_id(); ?>">
<div class="modal-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="quantity_shipped">Quantity(shipped):</label>
        <input type="number" step=".01" class="form-control form-control-sm" name="quantity" value="<?php echo $tracking_subitem-> get_quantity(); ?>">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="delivery_date">Delivery date:</label>
        <input type="text" class="form-control form-control-sm date" name="delivery_date" value="<?php echo $delivery_date; ?>">
      </div>
    </div>
  </div>
  <div class="form-group">
    <label for="tracking_number">Tracking #:</label>
    <textarea class="form-control form-control-sm" name="tracking_number" rows="5"><?php echo $tracking_subitem-> get_tracking_number(); ?></textarea>
  </div>
  <div class="form-group">
    <label for="signed_by">Signed by:</label>
    <input type="text" name="signed_by" class="form-control form-control-sm" value="<?php echo $tracking_subitem-> get_signed_by(); ?>">
  </div>
</div>
<div class="modal-footer">
  <button type="submit" name="save_tracking_subitem" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>
