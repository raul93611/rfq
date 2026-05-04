<?php
Conexion::abrir_conexion();
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $id_subitem);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem->obtener_id_item());
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $item->obtener_id_rfq());
Conexion::cerrar_conexion();
?>

<input type="hidden" name="id_subitem" value="<?= htmlspecialchars($id_subitem); ?>">
<input type="hidden" name="id_rfq" value="<?= htmlspecialchars($item->obtener_id_rfq()); ?>">

<div class="card-body user-form">

  <div class="item-comparison-grid">
    <div class="item-panel item-panel--project">
      <div class="item-panel-header"><i class="fas fa-file-alt mr-2"></i> Project Specifications</div>
      <div class="item-panel-body">
        <div class="form-group">
          <label for="brand_project">Brand</label>
          <input type="text" class="form-control form-control-sm" id="brand_project" name="brand_project" placeholder="Brand ..." autofocus value="<?= htmlspecialchars($subitem->obtener_brand_project()); ?>">
          <input type="hidden" name="brand_project_original" value="<?= htmlspecialchars($subitem->obtener_brand_project()); ?>">
        </div>
        <div class="form-group">
          <label for="part_number_project">Part #</label>
          <input type="text" class="form-control form-control-sm" id="part_number_project" name="part_number_project" placeholder="Part number ..." value="<?= htmlspecialchars($subitem->obtener_part_number_project()); ?>">
          <input type="hidden" name="part_number_project_original" value="<?= htmlspecialchars($subitem->obtener_part_number_project()); ?>">
        </div>
        <div class="form-group mb-0">
          <label for="description_project">Description</label>
          <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description_project" name="description_project"><?= htmlspecialchars($subitem->obtener_description_project()); ?></textarea>
          <input type="hidden" name="description_project_original" value="<?= htmlspecialchars($subitem->obtener_description_project()); ?>">
        </div>
      </div>
    </div>
    <div class="item-panel item-panel--proposal">
      <div class="item-panel-header"><i class="fas fa-lightbulb mr-2"></i> E-logic Proposal</div>
      <div class="item-panel-body">
        <div class="form-group">
          <label for="brand">Brand</label>
          <input type="text" class="form-control form-control-sm" id="brand" name="brand" placeholder="Brand ..." value="<?= htmlspecialchars($subitem->obtener_brand()); ?>">
          <input type="hidden" name="brand_original" value="<?= htmlspecialchars($subitem->obtener_brand()); ?>">
        </div>
        <div class="form-group">
          <label for="part_number">Part #</label>
          <input type="text" class="form-control form-control-sm" id="part_number" name="part_number" placeholder="Part number ..." value="<?= htmlspecialchars($subitem->obtener_part_number()); ?>">
          <input type="hidden" name="part_number_original" value="<?= htmlspecialchars($subitem->obtener_part_number()); ?>">
        </div>
        <div class="form-group mb-0">
          <label for="description">Description</label>
          <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description" name="description"><?= htmlspecialchars($subitem->obtener_description()); ?></textarea>
          <input type="hidden" name="description_original" value="<?= htmlspecialchars($subitem->obtener_description()); ?>">
        </div>
      </div>
    </div>
  </div>

  <div class="item-extra-fields">
    <div class="form-row">
      <div class="form-group col-md-4">
        <label for="quantity">Quantity</label>
        <input type="number" class="form-control form-control-sm" id="quantity" name="quantity" required value="<?= htmlspecialchars($subitem->obtener_quantity()); ?>">
        <input type="hidden" name="quantity_original" value="<?= htmlspecialchars($subitem->obtener_quantity()); ?>">
      </div>
      <div class="form-group col-md-8">
        <label for="website">Website</label>
        <input type="text" class="form-control form-control-sm" id="website" name="website" placeholder="https://..." value="<?= htmlspecialchars($subitem->obtener_website()); ?>">
        <input type="hidden" name="website_original" value="<?= htmlspecialchars($subitem->obtener_website()); ?>">
      </div>
    </div>
    <div class="form-group mb-0">
      <label for="comments">Comments</label>
      <textarea class="summernote_textarea form-control form-control-sm" rows="4" placeholder="Additional comments ..." id="comments" name="comments"><?= htmlspecialchars($subitem->obtener_comments()); ?></textarea>
      <textarea name="comments_original" rows="8" cols="80" style="display:none;"><?= htmlspecialchars($subitem->obtener_comments()); ?></textarea>
    </div>
  </div>

</div>
<div class="card-footer d-flex justify-content-between align-items-center">
  <a href="<?= EDITAR_COTIZACION . '/' . htmlspecialchars($item->obtener_id_rfq()); ?>" class="btn btn-secondary btn-sm">
    <i class="fas fa-arrow-left mr-1"></i> Back to Quote
  </a>
  <button type="submit" class="btn btn-primary btn-sm" name="guardar_cambios_subitem">
    <i class="fa fa-check mr-1"></i> Save Changes
  </button>
</div>