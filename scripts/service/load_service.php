<?php
Conexion::abrir_conexion();
$service = ServiceRepository::get_service(Conexion::obtener_conexion(), $id_service);
Conexion::cerrar_conexion();
?>
<div class="form-group">
  <label for="description">Description:</label>
  <textarea name="description" rows="5" cols="80" class="form-control form-control-sm"><?php echo $service-> get_description(); ?></textarea>
</div>
<div class="form-group">
  <label for="quantity">Quantity:</label>
  <input type="number" step=".01" name="quantity" class="form-control form-control-sm" value="<?php echo $service-> get_quantity(); ?>">
</div>
<div class="form-group">
  <label for="unit_price">Unit Price:</label>
  <input type="number" step=".01" name="unit_price" class="form-control form-control-sm" value="<?php echo $service-> get_unit_price(); ?>">
</div>
<input type="hidden" name="id_rfq" value="<?php echo $service-> get_id_rfq(); ?>">
<input type="hidden" name="id_service" value="<?php echo $id_service; ?>">
