<?php
Conexion::abrir_conexion();
$subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $_POST['id']);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem-> obtener_id_item());
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_subitem" value="<?= $_POST['id']; ?>">
<input type="hidden" name="id_rfq" value="<?= $item->obtener_id_rfq(); ?>">
<div class="row">
  <div class="col-md-6">
    <h2>Project specifications</h2>
    <div class="form-group">
      <label for="brand_project">Brand:</label>
      <input type="text" class="form-control form-control-sm" id="brand_project" name="brand_project" placeholder="Brand ..." autofocus value="<?= $subitem->obtener_brand_project(); ?>">
      <input type="hidden" name="brand_project_original" value="<?= $subitem->obtener_brand_project(); ?>">
    </div>
    <div class="form-group">
      <label for="part_number_project">Part #:</label>
      <input type="text" class="form-control form-control-sm" id="part_number_project" name="part_number_project" placeholder="Part # ..." value="<?= $subitem->obtener_part_number_project(); ?>">
      <input type="hidden" name="part_number_project_original" value="<?= $subitem->obtener_part_number_project(); ?>">
    </div>
    <div class="form-group">
      <label for="description_project">Description:</label>
      <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description_project" name="description_project"><?= $subitem->obtener_description_project(); ?></textarea>
      <input type="hidden" name="description_project_original" value="<?= $subitem->obtener_description_project(); ?>">
    </div>
  </div>
  <div class="col-md-6">
    <h2>E-logic proposal</h2>
    <div class="form-group">
      <label for="brand">Brand:</label>
      <input type="text" class="form-control form-control-sm" id="brand" name="brand" placeholder="Brand ..." autofocus value="<?= $subitem->obtener_brand(); ?>">
      <input type="hidden" name="brand_original" value="<?= $subitem->obtener_brand(); ?>">
    </div>
    <div class="form-group">
      <label for="part_number">Part #:</label>
      <input type="text" class="form-control form-control-sm" id="part_number" name="part_number" placeholder="Part # ..." value="<?= $subitem->obtener_part_number(); ?>">
      <input type="hidden" name="part_number_original" value="<?= $subitem->obtener_part_number(); ?>">
    </div>
    <div class="form-group">
      <label for="description">Description:</label>
      <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description" name="description"><?= $subitem->obtener_description(); ?></textarea>
      <input type="hidden" name="description_original" value="<?= $subitem->obtener_description(); ?>">
    </div>
  </div>
</div>
<div class="form-group">
  <label for="quantity">Quantity:</label>
  <input type="number" class="form-control form-control-sm" id="quantity" name="quantity" required value="<?= $subitem->obtener_quantity(); ?>">
  <input type="hidden" name="quantity_original" value="<?= $subitem->obtener_quantity(); ?>">
</div>
<div class="form-group">
  <label for="comments">Comments:</label>
  <textarea class="summernote_textarea form-control form-control-sm" rows="5" placeholder="Enter comments ..." id="comments" name="comments"><?= $subitem->obtener_comments(); ?></textarea>
  <textarea name="comments_original" rows="8" cols="80" style="display:none;"><?= $subitem->obtener_comments(); ?></textarea>
</div>
<div class="form-group">
  <label for="website">Website:</label>
  <input type="text" class="form-control form-control-sm" id="website" name="website" placeholder="Website ..." value="<?= $subitem->obtener_website(); ?>">
  <input type="hidden" name="website_original" value="<?= $subitem->obtener_website(); ?>">
</div>