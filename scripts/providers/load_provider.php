<?php
Conexion::abrir_conexion();
$provider = ProviderListRepository::get_one(Conexion::obtener_conexion(), $id_provider);
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_provider" value="<?php echo $id_provider;?>">
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" class="form-control form-control-sm" name="name" value="<?php echo $provider-> get_company_name(); ?>" required>
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
