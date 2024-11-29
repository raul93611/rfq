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
      <h2>Project Specifications</h2>
      <div class="form-group">
        <label for="brand_project">Brand:</label>
        <input type="text" class="form-control form-control-sm" name="brand_project" autofocus>
        <small class="form-text text-muted">Enter the brand name associated with the project.</small>
      </div>
      <div class="form-group">
        <label for="part_number_project">Part #:</label>
        <input type="text" class="form-control form-control-sm" name="part_number_project">
        <small class="form-text text-muted">Provide the part number for reference.</small>
      </div>
      <div class="form-group">
        <label for="description_project">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" name="description_project"></textarea>
        <small class="form-text text-muted">Include a detailed description of the project specifications.</small>
      </div>
    </div>
    <div class="col-md-6">
      <h2>E-logic Proposal</h2>
      <div class="form-group">
        <label for="brand">Brand:</label>
        <input type="text" class="form-control form-control-sm" name="brand">
        <small class="form-text text-muted">Enter the brand name for the E-logic proposal.</small>
      </div>
      <div class="form-group">
        <label for="part_number">Part #:</label>
        <input type="text" class="form-control form-control-sm" name="part_number">
        <small class="form-text text-muted">Specify the part number for this proposal.</small>
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" name="description"></textarea>
        <small class="form-text text-muted">Provide details about the E-logic proposal.</small>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label for="quantity">Quantity:</label>
    <input type="number" class="form-control form-control-sm" name="quantity">
    <small class="form-text text-muted">Enter the quantity of items needed.</small>
  </div>
  <div class="form-group">
    <label for="comments">Comments:</label>
    <textarea class="form-control form-control-sm" rows="5" name="comments"></textarea>
    <small class="form-text text-muted">Any additional comments regarding the project.</small>
  </div>
  <div class="form-group">
    <label for="website">Website:</label>
    <input type="text" class="form-control form-control-sm" name="website">
    <small class="form-text text-muted">Include a relevant website if applicable.</small>
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="save_re_quote_subitem">
    <i class="fa fa-check"></i> Save
  </button>
  <a href="<?= RE_QUOTE . $re_quote->get_id_rfq(); ?>" class="btn btn-danger">
    <i class="fa fa-times"></i> Cancel
  </a>
</div>