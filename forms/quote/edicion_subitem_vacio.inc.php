<?php
Conexion::abrir_conexion();
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $id_subitem);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem->obtener_id_item());
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $item->obtener_id_rfq());
Conexion::cerrar_conexion();
?>

<input type="hidden" name="id_subitem" value="<?= htmlspecialchars($id_subitem); ?>">
<input type="hidden" name="id_rfq" value="<?= htmlspecialchars($item->obtener_id_rfq()); ?>">

<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <h2>Project Specifications</h2>
      <div class="form-group">
        <label for="brand_project">Brand:</label>
        <input type="text" class="form-control form-control-sm" id="brand_project" name="brand_project" placeholder="Brand ..." value="<?= htmlspecialchars($subitem->obtener_brand_project()); ?>" autofocus>
        <input type="hidden" name="brand_project_original" value="<?= htmlspecialchars($subitem->obtener_brand_project()); ?>">
        <small class="form-text text-muted">Enter the brand name associated with the project.</small>
      </div>

      <div class="form-group">
        <label for="part_number_project">Part #:</label>
        <input type="text" class="form-control form-control-sm" id="part_number_project" name="part_number_project" placeholder="Part # ..." value="<?= htmlspecialchars($subitem->obtener_part_number_project()); ?>">
        <input type="hidden" name="part_number_project_original" value="<?= htmlspecialchars($subitem->obtener_part_number_project()); ?>">
        <small class="form-text text-muted">Specify the part number for the project.</small>
      </div>

      <div class="form-group">
        <label for="description_project">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description_project" name="description_project"><?= htmlspecialchars($subitem->obtener_description_project()); ?></textarea>
        <input type="hidden" name="description_project_original" value="<?= htmlspecialchars($subitem->obtener_description_project()); ?>">
        <small class="form-text text-muted">Provide a detailed description of the project specifications.</small>
      </div>
    </div>

    <div class="col-md-6">
      <h2>E-logic Proposal</h2>
      <div class="form-group">
        <label for="brand">Brand:</label>
        <input type="text" class="form-control form-control-sm" id="brand" name="brand" placeholder="Brand ..." value="<?= htmlspecialchars($subitem->obtener_brand()); ?>">
        <input type="hidden" name="brand_original" value="<?= htmlspecialchars($subitem->obtener_brand()); ?>">
        <small class="form-text text-muted">Enter the brand name of the proposal.</small>
      </div>

      <div class="form-group">
        <label for="part_number">Part #:</label>
        <input type="text" class="form-control form-control-sm" id="part_number" name="part_number" placeholder="Part # ..." value="<?= htmlspecialchars($subitem->obtener_part_number()); ?>">
        <input type="hidden" name="part_number_original" value="<?= htmlspecialchars($subitem->obtener_part_number()); ?>">
        <small class="form-text text-muted">Specify the part number for this proposal.</small>
      </div>

      <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description" name="description"><?= htmlspecialchars($subitem->obtener_description()); ?></textarea>
        <input type="hidden" name="description_original" value="<?= htmlspecialchars($subitem->obtener_description()); ?>">
        <small class="form-text text-muted">Provide a detailed description of the proposal.</small>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label for="quantity">Quantity:</label>
    <input type="number" class="form-control form-control-sm" id="quantity" name="quantity" required value="<?= htmlspecialchars($subitem->obtener_quantity()); ?>">
    <input type="hidden" name="quantity_original" value="<?= htmlspecialchars($subitem->obtener_quantity()); ?>">
    <small class="form-text text-muted">Specify the quantity for the order.</small>
  </div>

  <div class="form-group">
    <label for="comments">Comments:</label>
    <textarea class="summernote_textarea form-control form-control-sm" rows="5" placeholder="Enter comments ..." id="comments" name="comments"><?= htmlspecialchars($subitem->obtener_comments()); ?></textarea>
    <textarea name="comments_original" rows="8" cols="80" style="display: none;"><?= htmlspecialchars($subitem->obtener_comments()); ?></textarea>
    <small class="form-text text-muted">Any additional comments or notes related to this submission.</small>
  </div>

  <div class="form-group">
    <label for="website">Website:</label>
    <input type="text" class="form-control form-control-sm" id="website" name="website" placeholder="Website ..." value="<?= htmlspecialchars($subitem->obtener_website()); ?>">
    <input type="hidden" name="website_original" value="<?= htmlspecialchars($subitem->obtener_website()); ?>">
    <small class="form-text text-muted">Enter the website URL related to the project or proposal.</small>
  </div>
</div>

<div class="card-footer">
  <button type="submit" class="btn btn-success" name="guardar_cambios_subitem">
    <i class="fa fa-check"></i> Save
  </button>
  <a href="<?= EDITAR_COTIZACION . '/' . htmlspecialchars($item->obtener_id_rfq()); ?>" class="btn btn-danger">
    <i class="fa fa-times"></i> Cancel
  </a>
</div>