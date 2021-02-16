<?php
Conexion::abrir_conexion();
$re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Conexion::obtener_conexion(), $id_re_quote_item);
$re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $re_quote_item-> get_id_re_quote());
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_re_quote_item" value="<?php echo $id_re_quote_item; ?>">
<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="provider">Provider:</label>
        <input type="text" class="form-control form-control-sm" name="provider" autofocus required>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" step=".01" class="form-control form-control-sm" name="price" required>
      </div>
    </div>
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="save_re_quote_provider"><i class="fa fa-check"></i> Save</button>
  <a href="<?php echo RE_QUOTE . $re_quote-> get_id_rfq(); ?>" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
</div>
