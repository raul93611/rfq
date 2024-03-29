<?php
Conexion::abrir_conexion();
$provider = RepositorioProvider::obtener_provider_por_id(Conexion::obtener_conexion(), $id_provider);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $provider->obtener_id_item());
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $item-> obtener_id_rfq());
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_provider" value="<?php echo $id_provider; ?>">
<input type="hidden" name="id_rfq" value="<?php echo $item-> obtener_id_rfq(); ?>">
<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="provider">Provider:</label>
        <input type="text" class="form-control form-control-sm" id="provider" name="provider" placeholder="Provider ..." autofocus required value="<?php echo $provider-> obtener_provider(); ?>">
        <input type="hidden" name="provider_original" value="<?php echo $provider-> obtener_provider(); ?>">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" step=".01" class="form-control form-control-sm" id="price" name="price" required value="<?php echo $provider-> obtener_price(); ?>">
        <input type="hidden" name="price_original" value="<?php echo $provider-> obtener_price(); ?>">
      </div>
    </div>
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="guardar_cambios_provider"><i class="fa fa-check"></i> Save</button>
  <a href="<?php echo EDITAR_COTIZACION . '/' . $item-> obtener_id_rfq(); ?>" class="btn btn-info"><i class="fa fa-times"></i> Cancel</a>
  <a href="<?php echo DELETE_PROVIDER . '/' . $id_provider; ?>" class="delete_provider_item_button btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
</div>
