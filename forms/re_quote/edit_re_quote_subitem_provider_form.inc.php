<?php
Conexion::abrir_conexion();
$re_quote_subitem_provider = ReQuoteSubitemProviderRepository::get_re_quote_subitem_provider_by_id(Conexion::obtener_conexion(), $id_re_quote_subitem_provider);
$re_quote_subitem = ReQuoteSubitemRepository::get_re_quote_subitem_by_id(Conexion::obtener_conexion(), $re_quote_subitem_provider->get_id_re_quote_subitem());
$re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Conexion::obtener_conexion(), $re_quote_subitem->get_id_re_quote_item());
$re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $re_quote_item->get_id_re_quote());
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_re_quote_subitem_provider" value="<?php echo htmlspecialchars($id_re_quote_subitem_provider); ?>">
<div class="card-body user-form">
  <div class="form-row">
    <div class="form-group col-md-7">
      <label for="provider">Provider</label>
      <input type="text" class="form-control form-control-sm" id="provider" name="provider" placeholder="Provider name ..." value="<?php echo htmlspecialchars($re_quote_subitem_provider->get_provider()); ?>" autofocus required>
      <input type="hidden" name="provider_original" value="<?php echo htmlspecialchars($re_quote_subitem_provider->get_provider()); ?>">
    </div>
    <div class="form-group col-md-5">
      <label for="price">Price (USD)</label>
      <div class="input-group input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">$</span>
        </div>
        <input type="number" step=".01" min="0" class="form-control" id="price" name="price" placeholder="0.00" value="<?php echo htmlspecialchars($re_quote_subitem_provider->get_price()); ?>" required>
        <input type="hidden" name="price_original" value="<?php echo htmlspecialchars($re_quote_subitem_provider->get_price()); ?>">
      </div>
    </div>
  </div>
</div>
<div class="card-footer d-flex align-items-center" style="gap:8px;background:transparent;border-top:1px solid #f0f0f0;">
  <a href="<?php echo DELETE_RE_QUOTE_SUBITEM_PROVIDER . $re_quote_subitem_provider->get_id(); ?>" class="delete_provider_subitem_button btn btn-danger btn-sm"><i class="fa fa-trash mr-1"></i> Delete</a>
  <div class="ml-auto d-flex" style="gap:8px;">
    <button type="submit" class="btn btn-primary btn-sm" name="save_edit_re_quote_subitem_provider"><i class="fa fa-check mr-1"></i> Save</button>
    <a href="<?php echo RE_QUOTE . $re_quote->get_id_rfq(); ?>" class="btn btn-secondary btn-sm"><i class="fa fa-times mr-1"></i> Cancel</a>
  </div>
</div>