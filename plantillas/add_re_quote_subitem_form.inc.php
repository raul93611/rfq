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
      <h2>Project specifications</h2>
      <div class="form-group">
        <label for="brand_project">Brand:</label>
        <input type="text" class="form-control form-control-sm" name="brand_project" autofocus>
      </div>
      <div class="form-group">
        <label for="part_number_project">Part #:</label>
        <input type="text" class="form-control form-control-sm" name="part_number_project">
      </div>
      <div class="form-group">
        <label for="description_project">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" name="description_project"></textarea>
      </div>
    </div>
    <div class="col-md-6">
      <h2>E-logic proposal</h2>
      <div class="form-group">
        <label for="brand">Brand:</label>
        <input type="text" class="form-control form-control-sm" name="brand">
      </div>
      <div class="form-group">
        <label for="part_number">Part #:</label>
        <input type="text" class="form-control form-control-sm" name="part_number">
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" name="description"></textarea>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label for="quantity">Quantity:</label>
    <input type="number" class="form-control form-control-sm" name="quantity">
  </div>
  <div class="form-group">
    <label for="comments">Comments:</label>
    <textarea class="form-control form-control-sm" rows="5" name="comments"></textarea>
  </div>
  <div class="form-group">
    <label for="website">Website:</label>
    <input type="text" class="form-control form-control-sm" name="website">
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="save_re_quote_subitem"><i class="fa fa-check"></i> Save</button>
  <a href="<?php echo RE_QUOTE . $re_quote-> get_id_rfq(); ?>" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
</div>
