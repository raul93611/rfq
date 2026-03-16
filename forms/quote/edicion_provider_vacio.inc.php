<?php
Conexion::abrir_conexion();
$provider = RepositorioProvider::obtener_provider_por_id(Conexion::obtener_conexion(), $id_provider);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $provider->obtener_id_item());
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $item->obtener_id_rfq());
Conexion::cerrar_conexion();
?>

<input type="hidden" name="id_provider" value="<?= htmlspecialchars($id_provider); ?>">
<input type="hidden" name="id_rfq" value="<?= htmlspecialchars($item->obtener_id_rfq()); ?>">
<input type="hidden" name="provider_original" value="<?= htmlspecialchars($provider->obtener_provider()); ?>">
<input type="hidden" name="price_original" value="<?= htmlspecialchars($provider->obtener_price()); ?>">

<div class="card-body user-form">
  <div class="form-row">
    <div class="form-group col-md-7">
      <label for="provider">Provider</label>
      <input type="text" class="form-control form-control-sm" id="provider" name="provider" placeholder="Provider name ..." autofocus required value="<?= htmlspecialchars($provider->obtener_provider()); ?>">
    </div>
    <div class="form-group col-md-5">
      <label for="price">Price (USD)</label>
      <div class="input-group input-group">
        <div class="input-group-prepend"><span class="input-group-text">$</span></div>
        <input type="number" step=".01" min="0" class="form-control" id="price" name="price" placeholder="0.00" required value="<?= htmlspecialchars($provider->obtener_price()); ?>">
      </div>
    </div>
  </div>
</div>

<div class="card-footer d-flex justify-content-between align-items-center">
  <a href="<?= DELETE_PROVIDER . '/' . htmlspecialchars($id_provider); ?>" class="delete_provider_item_button btn btn-danger btn-sm">
    <i class="fa fa-trash mr-1"></i> Delete
  </a>
  <div style="display:flex;gap:8px;">
    <a href="<?= EDITAR_COTIZACION . '/' . htmlspecialchars($item->obtener_id_rfq()); ?>" class="btn btn-secondary btn-sm">
      <i class="fa fa-times mr-1"></i> Cancel
    </a>
    <button type="submit" class="btn btn-primary btn-sm" name="guardar_cambios_provider">
      <i class="fa fa-check mr-1"></i> Save Changes
    </button>
  </div>
</div>