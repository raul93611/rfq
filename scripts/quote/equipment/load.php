<?php
Conexion::abrir_conexion();
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $_POST['id']);
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $item->obtener_id_rfq());
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_item" value="<?= $item->obtener_id(); ?>">
<input type="hidden" name="id_rfq" value="<?= $item->obtener_id_rfq(); ?>">
<div class="row">
  <div class="col-md-6">
    <h2>Project specifications</h2>
    <div class="form-group">
      <label for="brand_project">Brand:</label>
      <input type="text" class="form-control form-control-sm" id="brand_project" name="brand_project" placeholder="Brand ..." autofocus value="<?= $item->obtener_brand_project(); ?>">
      <input type="hidden" name="brand_project_original" value="<?= $item->obtener_brand_project(); ?>">
    </div>
    <div class="form-group">
      <label for="part_number_project">Part #:</label>
      <input type="text" class="form-control form-control-sm" id="part_number_project" name="part_number_project" placeholder="Part # ..." value="<?= $item->obtener_part_number_project(); ?>">
      <input type="hidden" name="part_number_project_original" value="<?= $item->obtener_part_number_project(); ?>">
    </div>
    <div class="form-group">
      <label for="description_project">Description:</label>
      <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description_project" name="description_project"><?= $item->obtener_description_project(); ?></textarea>
      <input type="hidden" name="description_project_original" value="<?= $item->obtener_description_project(); ?>">
    </div>
  </div>
  <div class="col-md-6">
    <h2>E-logic proposal</h2>
    <div class="form-group">
      <label for="brand">Brand:</label>
      <input type="text" class="form-control form-control-sm" id="brand" name="brand" placeholder="Brand ..." autofocus value="<?= $item->obtener_brand(); ?>">
      <input type="hidden" name="brand_original" value="<?= $item->obtener_brand(); ?>">
    </div>
    <div class="form-group">
      <label for="part_number">Part #:</label>
      <input type="text" class="form-control form-control-sm" id="part_number" name="part_number" placeholder="Part # ..." value="<?= $item->obtener_part_number(); ?>">
      <input type="hidden" name="part_number_original" value="<?= $item->obtener_part_number(); ?>">
    </div>
    <div class="form-group">
      <label for="description">Description:</label>
      <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description" name="description"><?= $item->obtener_description(); ?></textarea>
      <input type="hidden" name="description_original" value="<?= $item->obtener_description(); ?>">
    </div>
  </div>
</div>
<div class="form-group">
  <label for="quantity">Quantity:</label>
  <input type="number" class="form-control form-control-sm" id="quantity" name="quantity" required value="<?= $item->obtener_quantity(); ?>">
  <input type="hidden" name="quantity_original" value="<?= $item->obtener_quantity(); ?>">
</div>
<div class="form-group">
  <label for="comments">Comments:</label>
  <textarea class="summernote_textarea form-control form-control-sm" rows="5" placeholder="Enter comments ..." id="comments" name="comments"><?= $item->obtener_comments(); ?></textarea>
  <textarea name="comments_original" rows="8" cols="80" style="display:none;"><?= $item->obtener_comments(); ?></textarea>
</div>
<div class="form-group">
  <label for="website">Website:</label>
  <input type="text" class="form-control form-control-sm" id="website" name="website" placeholder="Website ..." value="<?= $item->obtener_website(); ?>">
  <input type="hidden" name="website_original" value="<?= $item->obtener_website(); ?>">
</div>