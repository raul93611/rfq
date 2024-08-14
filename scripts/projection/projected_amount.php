<?php
try {
  // Validate and sanitize input
  if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = intval($_POST['id']);
  } else {
    throw new Exception("Invalid ID");
  }

  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch monthly projection
  $monthly_projection = MonthlyProjectionRepository::getById($conexion, $id);

  // Close database connection
  Conexion::cerrar_conexion();

  if (!$monthly_projection) {
    throw new Exception("No monthly projection found");
  }
} catch (Exception $e) {
  // Handle exceptions and display error message
  echo "Error: " . $e->getMessage();
  exit();
}
?>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="projected_amount">Criteria:</label>
        <input type="number" class="form-control form-control-sm" name="projected_amount" min="0" step=".01" id="projected_amount" value="<?= htmlspecialchars($monthly_projection->getProjectedAmount(), ENT_QUOTES, 'UTF-8') ?>">
      </div>
    </div>
  </div>
</div>
<input type="hidden" name="id_monthly_projection" value="<?= htmlspecialchars($monthly_projection->getId(), ENT_QUOTES, 'UTF-8') ?>">
<div class="modal-footer">
  <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>