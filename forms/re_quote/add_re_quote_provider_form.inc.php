<?php
Conexion::abrir_conexion();
$re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Conexion::obtener_conexion(), $id_re_quote_item);
$re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $re_quote_item->get_id_re_quote());
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_re_quote_item" value="<?= $id_re_quote_item; ?>">
<div class="card-body user-form">
  <div class="form-row">
    <div class="form-group col-md-7">
      <label for="provider">Provider</label>
      <input type="text" class="form-control form-control-sm" id="provider" name="provider" placeholder="Provider name ..." autofocus required>
    </div>
    <div class="form-group col-md-5">
      <label for="price">Price (USD)</label>
      <div class="input-group input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">$</span>
        </div>
        <input type="number" step=".01" min="0" class="form-control" id="price" name="price" placeholder="0.00" required>
      </div>
    </div>
  </div>
</div>
<div class="card-footer d-flex justify-content-end" style="gap:8px;background:transparent;border-top:1px solid #f0f0f0;">
  <button type="submit" class="btn btn-primary btn-sm" name="save_re_quote_provider"><i class="fa fa-check mr-1"></i> Save</button>
  <a href="<?= RE_QUOTE . $re_quote->get_id_rfq();  ?>" class="btn btn-secondary btn-sm"><i class="fa fa-times mr-1"></i> Cancel</a>
</div>