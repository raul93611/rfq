<?php
// Open database connection
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

// Fetch tracking by ID
$tracking = TrackingRepository::get_tracking_by_id($conexion, $id_tracking);

// Close the connection
Conexion::cerrar_conexion();

// Ensure the tracking was fetched successfully
if (!$tracking) {
  echo "Error: Tracking not found.";
  exit;
}

// Convert dates to English format
$delivery_date = date("m/d/Y", strtotime($tracking->get_delivery_date()));
$due_date = date("m/d/Y", strtotime($tracking->get_due_date()));
?>

<input type="hidden" id="id_tracking" name="id_tracking" value="<?= htmlspecialchars($tracking->get_id()); ?>">
<div class="modal-body">
  <div class="form-group">
    <label for="quantity_shipped">Quantity (shipped):</label>
    <input type="number" step=".01" class="form-control form-control-sm" name="quantity" value="<?= htmlspecialchars($tracking->get_quantity()); ?>">
    <small class="form-text text-muted">Enter the quantity of items that were shipped.</small>
  </div>
  <div class="form-group">
    <label for="delivery_date">Delivery date:</label>
    <input type="text" id="delivery_date" class="form-control form-control-sm date" name="delivery_date" value="<?= htmlspecialchars($delivery_date); ?>">
    <small class="form-text text-muted">Specify the date the items were delivered.</small>
  </div>
  <div class="form-group">
    <label for="due_date">Due date:</label>
    <input type="text" id="due_date" class="form-control form-control-sm date" name="due_date" value="<?= htmlspecialchars($due_date); ?>">
    <small class="form-text text-muted">Specify the expected due date for delivery.</small>
  </div>
  <div class="form-group">
    <label for="carrier">Carrier:</label>
    <input type="text" id="carrier" name="carrier" class="form-control form-control-sm" value="<?= htmlspecialchars($tracking->get_carrier()); ?>">
    <small class="form-text text-muted">Enter the name of the shipping carrier (e.g., FedEx, UPS).</small>
  </div>
  <div class="form-group">
    <label for="tracking_number">Tracking #:</label>
    <textarea class="form-control form-control-sm" name="tracking_number" rows="5"><?= htmlspecialchars($tracking->get_tracking_number()); ?></textarea>
    <small class="form-text text-muted">Enter the tracking number for this shipment.</small>
  </div>
  <div class="form-group">
    <label for="signed_by">Signed by:</label>
    <input type="text" name="signed_by" class="form-control form-control-sm" value="<?= htmlspecialchars($tracking->get_signed_by()); ?>">
    <small class="form-text text-muted">Name of the person who signed for the delivery.</small>
  </div>
  <div class="form-group">
    <label for="comments">Comment:</label>
    <textarea class="form-control form-control-sm" name="comments" rows="5" id="comments"><?= htmlspecialchars($tracking->get_comments()); ?></textarea>
    <small class="form-text text-muted">Add any additional comments or information.</small>
  </div>
</div>
<div class="modal-footer">
  <button type="submit" name="save_edit_tracking" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>