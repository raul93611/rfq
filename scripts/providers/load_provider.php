<?php
// Ensure necessary data is provided
if (!isset($id_provider) || !is_numeric($id_provider)) {
  die("Invalid provider ID");
}

$id_provider = intval($id_provider);

try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch provider details
  $provider = ProviderListRepository::get_one($conexion, $id_provider);

  // Close database connection
  Conexion::cerrar_conexion();
} catch (Exception $e) {
  // Handle any errors
  Conexion::cerrar_conexion();
  die("Error fetching provider details: " . $e->getMessage());
}
?>
<input type="hidden" name="id_provider" value="<?= htmlspecialchars($id_provider); ?>">
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" class="form-control form-control-sm" name="name" value="<?= $provider->get_company_name(); ?>" required>
        <div class="error_message">
          Name cannot be empty and has to be different from existing ones.
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="submit" name="save" form="edit_provider_form" class="btn btn-success">Save</button>
</div>