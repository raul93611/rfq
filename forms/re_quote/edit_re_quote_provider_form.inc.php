<?php
Conexion::abrir_conexion();
$re_quote_provider = ReQuoteProviderRepository::get_re_quote_provider_by_id(Conexion::obtener_conexion(), $id_re_quote_provider);
$re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Conexion::obtener_conexion(), $re_quote_provider->get_id_re_quote_item());
$re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $re_quote_item->get_id_re_quote());
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_re_quote_provider" value="<?= $id_re_quote_provider; ?>">
<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="provider">Provider:</label>
        <input type="text" class="form-control form-control-sm" name="provider" value="<?= $re_quote_provider->get_provider(); ?>" required>
        <input type="hidden" name="provider_original" value="<?= $re_quote_provider->get_provider(); ?>">
        <small class="form-text text-muted">Enter the name of the provider for this quote.</small>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" step=".01" class="form-control form-control-sm" name="price" value="<?= $re_quote_provider->get_price(); ?>" required>
        <input type="hidden" name="price_original" value="<?= $re_quote_provider->get_price(); ?>">
        <small class="form-text text-muted">Specify the price for the provider's services or products.</small>
      </div>
    </div>
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-primary" name="save_edit_re_quote_provider">
    <i class="fa fa-check"></i> Save
  </button>
  <a href="<?= RE_QUOTE . $re_quote->get_id_rfq(); ?>" class="btn btn-secondary">
    <i class="fa fa-times"></i> Cancel
  </a>
  <a href="<?= DELETE_RE_QUOTE_PROVIDER . $id_re_quote_provider; ?>" class="delete_provider_item_button btn btn-secondary">
    <i class="fa fa-trash"></i> Delete
  </a>
</div>