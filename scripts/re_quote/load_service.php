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
  <label for="description">Description:</label>
  <textarea name="description" rows="5" cols="80" class="form-control form-control-sm"><?= htmlspecialchars($service->get_description()); ?></textarea>
</div>
<div class="form-group">
  <label for="quantity">Quantity:</label>
  <input type="number" step=".01" name="quantity" class="form-control form-control-sm" value="<?= htmlspecialchars($service->get_quantity()); ?>">
</div>
<div class="form-group">
  <label for="unit_price">Unit Price:</label>
  <input type="number" step=".01" name="unit_price" class="form-control form-control-sm" value="<?= htmlspecialchars($service->get_unit_price()); ?>">
</div>
<input type="hidden" name="id_re_quote" value="<?= htmlspecialchars($service->get_id_re_quote()); ?>">
<input type="hidden" name="id_service" value="<?= htmlspecialchars($id_service); ?>">