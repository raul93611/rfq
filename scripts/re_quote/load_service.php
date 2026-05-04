<?php
try {
  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch the service details
  $service = ReQuoteServiceRepository::get_service($conexion, $id_service);
} catch (Exception $e) {
  // Handle any exceptions by displaying an error message and terminating the script
  die('Error: ' . $e->getMessage());
} finally {
  // Ensure the connection is closed
  Conexion::cerrar_conexion();
}
?>

<div class="form-group">
  <label>Description</label>
  <textarea name="description" rows="4" class="form-control form-control-sm"><?= htmlspecialchars($service->get_description()); ?></textarea>
</div>
<div class="form-row">
  <div class="form-group col-md-6">
    <label>Quantity</label>
    <input type="number" step=".01" name="quantity" class="form-control form-control-sm" value="<?= htmlspecialchars($service->get_quantity()); ?>">
  </div>
  <div class="form-group col-md-6">
    <label>Unit Price (USD)</label>
    <div class="input-group input-group">
      <div class="input-group-prepend">
        <span class="input-group-text">$</span>
      </div>
      <input type="number" step=".01" name="unit_price" class="form-control" value="<?= htmlspecialchars($service->get_unit_price()); ?>">
    </div>
  </div>
</div>
<input type="hidden" name="id_re_quote" value="<?= htmlspecialchars($service->get_id_re_quote()); ?>">
<input type="hidden" name="id_service" value="<?= htmlspecialchars($id_service); ?>">