<?php
Conexion::abrir_conexion();
$re_quote_provider = ReQuoteProviderRepository::get_re_quote_provider_by_id(Conexion::obtener_conexion(), $id_re_quote_provider);
$re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Conexion::obtener_conexion(), $re_quote_provider->get_id_re_quote_item());
$re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $re_quote_item->get_id_re_quote());
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_re_quote_provider" value="<?= $id_re_quote_provider; ?>">
<div class="card-body user-form">
  <div class="form-row">
    <div class="form-group col-md-7">
      <label for="provider">Provider</label>
      <input type="text" class="form-control form-control-sm" id="provider" name="provider" placeholder="Provider name ..." value="<?= htmlspecialchars($re_quote_provider->get_provider()); ?>" required>
      <input type="hidden" name="provider_original" value="<?= htmlspecialchars($re_quote_provider->get_provider()); ?>">
    </div>
    <div class="form-group col-md-5">
      <label for="price">Price (USD)</label>
      <div class="input-group input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">$</span>
        </div>
        <input type="number" step=".01" min="0" class="form-control" id="price" name="price" placeholder="0.00" value="<?= htmlspecialchars($re_quote_provider->get_price()); ?>" required>
        <input type="hidden" name="price_original" value="<?= htmlspecialchars($re_quote_provider->get_price()); ?>">
      </div>
    </div>
  </div>
</div>
<div class="card-footer d-flex align-items-center" style="gap:8px;background:transparent;border-top:1px solid #f0f0f0;">
  <a href="<?= DELETE_RE_QUOTE_PROVIDER . $id_re_quote_provider; ?>" class="delete_provider_item_button btn btn-danger btn-sm"><i class="fa fa-trash mr-1"></i> Delete</a>
  <div class="ml-auto d-flex" style="gap:8px;">
    <a href="<?= RE_QUOTE . $re_quote->get_id_rfq(); ?>" class="btn btn-secondary btn-sm"><i class="fa fa-times mr-1"></i> Cancel</a>
    <button type="submit" class="btn btn-primary btn-sm" name="save_edit_re_quote_provider"><i class="fa fa-check mr-1"></i> Save</button>
  </div>
</div>