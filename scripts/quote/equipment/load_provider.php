<?php
Conexion::abrir_conexion();
$provider = RepositorioProvider::obtener_provider_por_id(Conexion::obtener_conexion(), $_POST['id']);
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_provider" value="<?= $_POST['id']; ?>">
<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label for="provider">Provider:</label>
      <input type="text" class="form-control form-control-sm" id="provider" name="provider" placeholder="Provider ..." autofocus required value="<?= $provider->obtener_provider(); ?>">
      <input type="hidden" name="provider_original" value="<?= $provider->obtener_provider(); ?>">
    </div>
    <div class="form-group">
      <label for="price">Price:</label>
      <input type="number" step=".01" class="form-control form-control-sm" id="price" name="price" required value="<?= $provider->obtener_price(); ?>">
      <input type="hidden" name="price_original" value="<?= $provider->obtener_price(); ?>">
    </div>
  </div>
</div>