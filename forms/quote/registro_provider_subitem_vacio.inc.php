<input type="hidden" name="id_subitem" value="<?= $id_subitem; ?>">
<?php
Conexion::abrir_conexion();
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $id_subitem);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem->obtener_id_item());
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $item->obtener_id_rfq());
Conexion::cerrar_conexion();
?>
<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="provider">Provider:</label>
        <input type="text" class="form-control form-control-sm" id="provider" name="provider" placeholder="Provider ..." autofocus required>
        <small class="form-text text-muted">Enter the provider's name</small>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" step=".01" class="form-control form-control-sm" id="price" name="price" required>
        <small class="form-text text-muted">Enter the price for this subitem (in USD)</small>
      </div>
    </div>
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="guardar_provider_subitem"><i class="fa fa-check"></i> Save</button>
  <a href="<?= EDITAR_COTIZACION . '/' . $item->obtener_id_rfq(); ?>" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
</div>