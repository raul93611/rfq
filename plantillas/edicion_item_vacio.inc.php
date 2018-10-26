<input type="hidden" name="id_item" value="<?php echo $id_item; ?>">
<input type="hidden" name="id_rfq" value="<?php echo $item->obtener_id_rfq(); ?>">
<?php
Conexion::abrir_conexion();
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $item-> obtener_id_rfq());
Conexion::cerrar_conexion();
if($cargo == 5 && $_SESSION['id_usuario'] != $cotizacion_recuperada-> obtener_usuario_designado()){
  Redireccion::redirigir1(PERFIL);
}
?>
<div class="card-body">
  <div class="row">
    <div class="col">
      <h2>Project specifications</h2>
      <div class="form-group">
        <label for="brand_project">Brand:</label>
        <input type="text" class="form-control form-control-sm" id="brand_project" name="brand_project" placeholder="Brand ..." autofocus value="<?php echo $item->obtener_brand_project(); ?>">
        <input type="hidden" name="brand_project_original" value="<?php echo $item->obtener_brand_project(); ?>">
      </div>
      <div class="form-group">
        <label for="part_number_project">Part #:</label>
        <input type="text" class="form-control form-control-sm" id="part_number_project" name="part_number_project" placeholder="Part # ..." value="<?php echo $item->obtener_part_number_project(); ?>">
        <input type="hidden" name="part_number_project_original" value="<?php echo $item->obtener_part_number_project(); ?>">
      </div>
      <div class="form-group">
        <label for="description_project">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description_project" name="description_project"><?php echo $item->obtener_description_project(); ?></textarea>
        <input type="hidden" name="description_project_original" value="<?php echo $item->obtener_description_project(); ?>">
      </div>
    </div>
    <div class="col">
      <h2>E-logic proposal</h2>
      <div class="form-group">
        <label for="brand">Brand:</label>
        <input type="text" class="form-control form-control-sm" id="brand" name="brand" placeholder="Brand ..." autofocus value="<?php echo $item->obtener_brand(); ?>">
        <input type="hidden" name="brand_original" value="<?php echo $item->obtener_brand(); ?>">
      </div>
      <div class="form-group">
        <label for="part_number">Part #:</label>
        <input type="text" class="form-control form-control-sm" id="part_number" name="part_number" placeholder="Part # ..." value="<?php echo $item->obtener_part_number(); ?>">
        <input type="hidden" name="part_number_original" value="<?php echo $item->obtener_part_number(); ?>">
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description" name="description"><?php echo $item-> obtener_description(); ?></textarea>
        <input type="hidden" name="description_original" value="<?php echo $item-> obtener_description(); ?>">
      </div>
    </div>
  </div>
  <div class="form-group">
    <label for="quantity">Quantity:</label>
    <input type="number" class="form-control form-control-sm" id="quantity" name="quantity" required value="<?php echo $item->obtener_quantity(); ?>">
    <input type="hidden" name="quantity_original" value="<?php echo $item->obtener_quantity(); ?>">
  </div>
  <div class="form-group">
    <label for="comments">Comments:</label>
    <textarea class="form-control form-control-sm" rows="5" placeholder="Enter comments ..." id="comments" name="comments"><?php echo $item->obtener_comments(); ?></textarea>
    <input type="hidden" name="comments_original" value="<?php echo $item->obtener_comments(); ?>">
  </div>
  <div class="form-group">
    <label for="website">Website:</label>
    <input type="text" class="form-control form-control-sm" id="website" name="website" placeholder="Website ..." value="<?php echo $item->obtener_website(); ?>">
    <input type="hidden" name="website_original" value="<?php echo $item->obtener_website(); ?>">
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="guardar_cambios_item"><i class="fa fa-check"></i> Save</button>
  <a href="<?php echo EDITAR_COTIZACION . '/' . $item-> obtener_id_rfq(); ?>" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
</div>
