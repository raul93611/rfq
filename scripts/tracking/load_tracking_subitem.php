<?php
Conexion::abrir_conexion();
$tracking_subitem = TrackingSubitemRepository::get_tracking_subitem_by_id(Conexion::obtener_conexion(), $id_tracking_subitem);
Conexion::cerrar_conexion();
$delivery_date = RepositorioComment::mysql_date_to_english_format($tracking_subitem->get_delivery_date());
$due_date = RepositorioComment::mysql_date_to_english_format($tracking_subitem->get_due_date());
?>
<input type="hidden" id="id_tracking_subitem" name="id_tracking_subitem" value="<?= $tracking_subitem->get_id(); ?>">
<div class="modal-body">
  <div class="form-group">
    <label for="quantity_shipped">Quantity(shipped):</label>
    <input type="number" step=".01" class="form-control form-control-sm" name="quantity" value="<?= $tracking_subitem->get_quantity(); ?>">
  </div>
  <div class="form-group">
    <label for="delivery_date">Delivery date:</label>
    <input type="text" class="form-control form-control-sm date" name="delivery_date" value="<?= $delivery_date; ?>">
  </div>
  <div class="form-group">
    <label for="due_date">Due date:</label>
    <input type="text" id="due_date" class="form-control form-control-sm date" name="due_date" value="<?= $due_date; ?>">
  </div>
  <div class="form-group">
    <label for="carrier">Carrier:</label>
    <input type="text" id="carrier" name="carrier" class="form-control form-control-sm" value="<?= $tracking_subitem->get_carrier(); ?>">
  </div>
  <div class="form-group">
    <label for="tracking_number">Tracking #:</label>
    <textarea class="form-control form-control-sm" name="tracking_number" rows="5"><?= $tracking_subitem->get_tracking_number(); ?></textarea>
  </div>
  <div class="form-group">
    <label for="signed_by">Signed by:</label>
    <input type="text" name="signed_by" class="form-control form-control-sm" value="<?= $tracking_subitem->get_signed_by(); ?>">
  </div>
  <div class="form-group">
    <label for="comments">Comment:</label>
    <textarea class="form-control form-control-sm" name="comments" rows="5" id="comments"><?= $tracking_subitem->get_comments(); ?></textarea>
  </div>
</div>
<div class="modal-footer">
  <button type="submit" name="save_tracking_subitem" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>