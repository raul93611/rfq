<?php
try {
  // Validate and sanitize input
  if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = intval($_POST['id']);
  } else {
    throw new Exception("Invalid ID provided.");
  }

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch monthly projection
  $monthly_projection = MonthlyProjectionRepository::getById($conexion, $id);

  // Close database connection
  Conexion::cerrar_conexion();

  if (!$monthly_projection) {
    throw new Exception("No monthly projection found for the given ID.");
  }
} catch (Exception $e) {
  // Display error message
  echo "<small class='text-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</small>";
  exit();
}
?>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="projected_amount">Projected Amount:</label>
        <input type="number" class="form-control form-control-sm" name="projected_amount" min="0" step=".01" id="projected_amount" value="<?= htmlspecialchars($monthly_projection->getProjectedAmount(), ENT_QUOTES, 'UTF-8') ?>">
        <small class="form-text text-muted">Enter the projected amount as a positive number with up to two decimal places.</small>
      </div>
    </div>
  </div>
</div>
<input type="hidden" name="id_monthly_projection" value="<?= htmlspecialchars($monthly_projection->getId(), ENT_QUOTES, 'UTF-8') ?>">
<div class="modal-footer">
  <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>