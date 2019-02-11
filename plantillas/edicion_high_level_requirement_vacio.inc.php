<input type="hidden" name="id_cuestionario" value="<?php echo $high_level_requirement-> obtener_id_cuestionario(); ?>">
<input type="hidden" name="id_high_level_requirement" value="<?php echo $id_high_level_requirement; ?>">
<?php
Conexion::abrir_conexion();
$cuestionario = RepositorioCuestionario::obtener_cuestionario_por_id(Conexion::obtener_conexion(), $high_level_requirement-> obtener_id_cuestionario());
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $cuestionario-> obtener_id_rfq());
Conexion::cerrar_conexion();
if($cargo == 5 && $_SESSION['id_usuario'] != $cotizacion_recuperada-> obtener_usuario_designado()){
  Redireccion::redirigir1(PERFIL);
}
?>
<div class="card-body">
  <div class="form-group">
    <label for="quantity">Requirement:</label>
    <input type="text" class="form-control form-control-sm" name="requirement" autofocus required value="<?php echo $high_level_requirement-> obtener_requirement(); ?>">
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="editar_high_level_requirement"><i class="fa fa-check"></i> Save</button>
  <a href="<?php echo CUESTIONARIO . '/' . $cuestionario-> obtener_id_rfq(); ?>" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
</div>
