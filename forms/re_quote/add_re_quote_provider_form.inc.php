<?php
Conexion::abrir_conexion();
$re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Conexion::obtener_conexion(), $id_re_quote_item);
$re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $re_quote_item->get_id_re_quote());
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_re_quote_item" value="<?= $id_re_quote_item; ?>">
<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="provider">Provider:</label>
        <input type="text" class="form-control form-control-sm" name="provider" autofocus required>
        <small class="form-text text-muted">Enter the name of the provider for this quote item.</small>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" step=".01" class="form-control form-control-sm" name="price" required>
        <small class="form-text text-muted">Specify the price of the item. Use two decimal places.</small>
      </div>
    </div>
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-primary" name="save_re_quote_provider">
    <i class="fa fa-check"></i> Save
  </button>
  <a href="<?= RE_QUOTE . $re_quote->get_id_rfq(); ?>" class="btn btn-secondary">
    <i class="fa fa-times"></i> Cancel
  </a>
</div>