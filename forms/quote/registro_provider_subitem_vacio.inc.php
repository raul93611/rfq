<input type="hidden" name="id_subitem" value="<?= $id_subitem; ?>">
<div class="card-body user-form">
  <div class="form-row">
    <div class="form-group col-md-7">
      <label for="provider">Provider</label>
      <input type="text" class="form-control form-control-sm" id="provider" name="provider" placeholder="Provider name ..." autofocus required>
    </div>
    <div class="form-group col-md-5">
      <label for="price">Price (USD)</label>
      <div class="input-group input-group">
        <div class="input-group-prepend"><span class="input-group-text">$</span></div>
        <input type="number" step=".01" min="0" class="form-control" id="price" name="price" placeholder="0.00" required>
      </div>
    </div>
  </div>
</div>
<div class="card-footer d-flex justify-content-end" style="gap:8px;">
  <a href="<?= EDITAR_COTIZACION . '/' . $_hdr_item->obtener_id_rfq(); ?>" class="btn btn-secondary btn-sm">
    <i class="fa fa-times mr-1"></i> Cancel
  </a>
  <button type="submit" class="btn btn-primary btn-sm" name="guardar_provider_subitem">
    <i class="fa fa-check mr-1"></i> Save Provider
  </button>
</div>