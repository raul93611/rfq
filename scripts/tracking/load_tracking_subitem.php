<?php
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

// Fetch the tracking subitem by ID
$tracking_subitem = TrackingSubitemRepository::get_tracking_subitem_by_id($conexion, $id_tracking_subitem);

Conexion::cerrar_conexion();

// Ensure the tracking subitem was fetched successfully
if (!$tracking_subitem) {
  echo "Error: Tracking subitem not found.";
  exit;
}

// Convert dates to English format
$delivery_date = date("m/d/Y", strtotime($tracking_subitem->get_delivery_date()));
$due_date = date("m/d/Y", strtotime($tracking_subitem->get_due_date()));
?>
<input type="hidden" id="id_tracking_subitem" name="id_tracking_subitem" value="<?= htmlspecialchars($tracking_subitem->get_id()); ?>">
<div class="modal-body">
  <div class="form-group">
    <label for="quantity_shipped">Quantity (shipped):</label>
    <input type="number" step=".01" class="form-control form-control-sm" name="quantity" value="<?= htmlspecialchars($tracking_subitem->get_quantity()); ?>">
  </div>
  <div class="form-group">
    <label for="delivery_date">Delivery date:</label>
    <input type="text" class="form-control form-control-sm date" name="delivery_date" value="<?= htmlspecialchars($delivery_date); ?>">
  </div>
  <div class="form-group">
    <label for="due_date">Due date:</label>
    <input type="text" id="due_date" class="form-control form-control-sm date" name="due_date" value="<?= htmlspecialchars($due_date); ?>">
  </div>
  <div class="form-group">
    <label for="carrier">Carrier:</label>
    <input type="text" id="carrier" name="carrier" class="form-control form-control-sm" value="<?= htmlspecialchars($tracking_subitem->get_carrier()); ?>">
  </div>
  <div class="form-group">
    <label for="tracking_number">Tracking #:</label>
    <textarea class="form-control form-control-sm" name="tracking_number" rows="5"><?= htmlspecialchars($tracking_subitem->get_tracking_number()); ?></textarea>
  </div>
  <div class="form-group">
    <label for="signed_by">Signed by:</label>
    <input type="text" name="signed_by" class="form-control form-control-sm" value="<?= htmlspecialchars($tracking_subitem->get_signed_by()); ?>">
  </div>
  <div class="form-group">
    <label for="comments">Comment:</label>
    <textarea class="form-control form-control-sm" name="comments" rows="5" id="comments"><?= htmlspecialchars($tracking_subitem->get_comments()); ?></textarea>
  </div>
</div>
<div class="modal-footer">
  <button type="submit" name="save_tracking_subitem" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>