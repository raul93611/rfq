<input type="hidden" name="id_cuestionario" value="<?php echo $project_milestone-> obtener_id_cuestionario(); ?>">
<input type="hidden" name="id_project_milestone" value="<?php echo $id_project_milestone; ?>">
<?php
Conexion::abrir_conexion();
$cuestionario = RepositorioCuestionario::obtener_cuestionario_por_id(Conexion::obtener_conexion(), $project_milestone-> obtener_id_cuestionario());
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $cuestionario-> obtener_id_rfq());
Conexion::cerrar_conexion();
if($cargo == 5 && $_SESSION['id_usuario'] != $cotizacion_recuperada-> obtener_usuario_designado()){
  Redireccion::redirigir1(PERFIL);
}
?>
<div class="card-body">
    <div class="form-group">
        <label>Date:</label>
        <input type="text" class="form-control form-control-sm" id="date_milestone" name="date_milestone" autofocus required value="<?php echo $project_milestone-> obtener_date_milestone(); ?>">
    </div>
    <div class="form-group">
        <label>Description:</label>
        <input type="text" class="form-control form-control-sm" name="description" required value="<?php echo $project_milestone-> obtener_description(); ?>">
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-success" name="editar_project_milestone"><i class="fa fa-check"></i> Save</button>
    <a href="<?php echo CUESTIONARIO . '/' . $cuestionario-> obtener_id_rfq(); ?>" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
</div>
