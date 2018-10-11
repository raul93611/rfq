<?php
Conexion::abrir_conexion();
$provider_subitem = RepositorioProviderSubitem::obtener_provider_subitem_por_id(Conexion::obtener_conexion(),$id_provider_subitem);
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem-> obtener_id_item());
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $item-> obtener_id_rfq());
Conexion::cerrar_conexion();
if($cargo == 5 && $_SESSION['id_usuario'] != $cotizacion_recuperada-> obtener_usuario_designado()){
  Redireccion::redirigir1(PERFIL);
}
?>
<input type="hidden" name="id_provider_subitem" value="<?php echo $id_provider_subitem; ?>">
<input type="hidden" name="id_rfq" value="<?php echo $item-> obtener_id_rfq(); ?>">

<div class="card-body">
  <div class="row">
    <div class="col">
      <div class="form-group">
        <label for="provider">Provider:</label>
        <input type="text" class="form-control form-control-sm" id="provider" name="provider" placeholder="Provider ..." autofocus required value="<?php echo $provider_subitem-> obtener_provider(); ?>">
      </div>
    </div>
    <div class="col">
      <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" step=".01" class="form-control form-control-sm" id="price" name="price" required value="<?php echo $provider_subitem-> obtener_price(); ?>">
      </div>
    </div>
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="guardar_cambios_provider_subitem"><i class="fa fa-check"></i> Save</button>
  <a href="<?php echo EDITAR_COTIZACION . '/' . $item-> obtener_id_rfq(); ?>" class="btn btn-info"><i class="fa fa-times"></i> Cancel</a>
  <a href="<?php echo DELETE_PROVIDER_SUBITEM . '/' . $id_provider_subitem; ?>" class="delete_provider_subitem_button btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
</div>
