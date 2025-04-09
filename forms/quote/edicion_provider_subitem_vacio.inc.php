<?php
Conexion::abrir_conexion();
$provider_subitem = RepositorioProviderSubitem::obtener_provider_subitem_por_id(Conexion::obtener_conexion(), $id_provider_subitem);
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $provider_subitem->obtener_id_subitem());
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem->obtener_id_item());
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $item->obtener_id_rfq());
Conexion::cerrar_conexion();
?>

<input type="hidden" name="id_provider_subitem" value="<?= htmlspecialchars($id_provider_subitem); ?>">
<input type="hidden" name="id_rfq" value="<?= htmlspecialchars($item->obtener_id_rfq()); ?>">

<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="provider">Provider:</label>
        <input type="text" class="form-control form-control-sm" id="provider" name="provider" placeholder="Provider ..." autofocus required value="<?= htmlspecialchars($provider_subitem->obtener_provider()); ?>">
        <small class="form-text text-muted">Enter the name of the provider for this subitem.</small>
        <input type="hidden" name="provider_original" value="<?= htmlspecialchars($provider_subitem->obtener_provider()); ?>">
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" step=".01" class="form-control form-control-sm" id="price" name="price" required value="<?= htmlspecialchars($provider_subitem->obtener_price()); ?>">
        <small class="form-text text-muted">Enter the price for this provider's subitem.</small>
        <input type="hidden" name="price_original" value="<?= htmlspecialchars($provider_subitem->obtener_price()); ?>">
      </div>
    </div>
  </div>
</div>

<div class="card-footer">
  <button type="submit" class="btn btn-primary" name="guardar_cambios_provider_subitem">
    <i class="fa fa-check"></i> Save
  </button>
  <a href="<?= EDITAR_COTIZACION . '/' . htmlspecialchars($item->obtener_id_rfq()); ?>" class="btn btn-secondary">
    <i class="fa fa-times"></i> Cancel
  </a>
  <a href="<?= DELETE_PROVIDER_SUBITEM . '/' . htmlspecialchars($id_provider_subitem); ?>" class="delete_provider_subitem_button btn btn-secondary">
    <i class="fa fa-trash"></i> Delete
  </a>
</div>