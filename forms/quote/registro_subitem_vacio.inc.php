<input type="hidden" name="id_item" value="<?= $id_item; ?>">
<?php
Conexion::abrir_conexion();
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $id_item);
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $item->obtener_id_rfq());
Conexion::cerrar_conexion();
?>
<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <h2>Project Specifications</h2>
      <div class="form-group">
        <label for="brand_project">Brand:</label>
        <input type="text" class="form-control form-control-sm" id="brand_project" name="brand_project" placeholder="Brand ..." autofocus>
        <small class="form-text text-muted">Enter the brand name for the project.</small>
      </div>
      <div class="form-group">
        <label for="part_number_project">Part #:</label>
        <input type="text" class="form-control form-control-sm" id="part_number_project" name="part_number_project" placeholder="Part # ...">
        <small class="form-text text-muted">Provide the part number associated with the project.</small>
      </div>
      <div class="form-group">
        <label for="description_project">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description_project" name="description_project"></textarea>
        <small class="form-text text-muted">Enter a brief description of the project.</small>
      </div>
    </div>
    <div class="col-md-6">
      <h2>E-logic Proposal</h2>
      <div class="form-group">
        <label for="brand">Brand:</label>
        <input type="text" class="form-control form-control-sm" id="brand" name="brand" placeholder="Brand ...">
        <small class="form-text text-muted">Enter the brand name for the E-logic proposal.</small>
      </div>
      <div class="form-group">
        <label for="part_number">Part #:</label>
        <input type="text" class="form-control form-control-sm" id="part_number" name="part_number" placeholder="Part # ...">
        <small class="form-text text-muted">Provide the part number for the E-logic proposal.</small>
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description" name="description"></textarea>
        <small class="form-text text-muted">Enter a description for the E-logic proposal.</small>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label for="quantity">Quantity:</label>
    <input type="number" class="form-control form-control-sm" id="quantity" name="quantity">
    <small class="form-text text-muted">Specify the quantity of items.</small>
  </div>
  <div class="form-group">
    <label for="comments">Comments:</label>
    <textarea class="summernote_textarea form-control form-control-sm" rows="5" placeholder="Enter comments ..." id="comments" name="comments"></textarea>
    <small class="form-text text-muted">Add any additional comments here.</small>
  </div>
  <div class="form-group">
    <label for="website">Website:</label>
    <input type="text" class="form-control form-control-sm" id="website" name="website" placeholder="Website ...">
    <small class="form-text text-muted">Enter the website URL related to this item.</small>
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="guardar_subitem"><i class="fa fa-check"></i> Save</button>
  <a href="<?= EDITAR_COTIZACION . '/' . $item->obtener_id_rfq(); ?>" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
</div>