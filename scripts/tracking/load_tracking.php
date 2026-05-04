<?php
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$tracking = TrackingRepository::get_tracking_by_id($conexion, $id_tracking);
Conexion::cerrar_conexion();

if (!$tracking) {
  echo "Error: Tracking not found.";
  exit;
}

$delivery_date = date("m/d/Y", strtotime($tracking->get_delivery_date()));
$due_date = date("m/d/Y", strtotime($tracking->get_due_date()));
?>

<input type="hidden" id="id_tracking" name="id_tracking" value="<?= htmlspecialchars($tracking->get_id()); ?>">

<div class="form-group">
  <label>Quantity (shipped)</label>
  <input type="number" step=".01" class="form-control form-control-sm" name="quantity" value="<?= htmlspecialchars($tracking->get_quantity()); ?>">
</div>

<div class="form-row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Delivery Date</label>
      <input type="text" id="delivery_date" class="form-control form-control-sm date" name="delivery_date" value="<?= htmlspecialchars($delivery_date); ?>">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Due Date</label>
      <input type="text" id="due_date" class="form-control form-control-sm date" name="due_date" value="<?= htmlspecialchars($due_date); ?>">
    </div>
  </div>
</div>

<div class="form-row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Carrier</label>
      <input type="text" name="carrier" class="form-control form-control-sm" value="<?= htmlspecialchars($tracking->get_carrier()); ?>">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Signed By</label>
      <input type="text" name="signed_by" class="form-control form-control-sm" value="<?= htmlspecialchars($tracking->get_signed_by()); ?>">
    </div>
  </div>
</div>

<div class="form-group">
  <label>Tracking #</label>
  <textarea class="form-control form-control-sm" name="tracking_number" rows="3"><?= htmlspecialchars($tracking->get_tracking_number()); ?></textarea>
</div>

<div class="form-group">
  <label>Comments</label>
  <textarea class="form-control form-control-sm" name="comments" rows="3"><?= htmlspecialchars($tracking->get_comments()); ?></textarea>
</div>
