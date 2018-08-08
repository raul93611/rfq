<?php
Conexion::abrir_conexion();
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $cuestionario-> obtener_id_rfq());
Conexion::cerrar_conexion();
if($cargo == 5 && $_SESSION['id_usuario'] != $cotizacion_recuperada-> obtener_usuario_designado()){
  Redireccion::redirigir1(PERFIL);
}
?>
<input type="hidden" name="id_cuestionario" value="<?php echo $id_cuestionario; ?>">
<div class="card-body">
    <div class="form-group">
        <label>Date:</label>
        <input type="text" id="date_milestone" class="form-control" name="date_milestone" required>
    </div>
    <div class="form-group">
        <label>Description:</label>
        <input type="text" class="form-control" name="description" required>
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-success" name="guardar_project_milestone"><i class="fa fa-check"></i> Save</button>
    <a href="<?php echo CUESTIONARIO . '/' . $cuestionario-> obtener_id_rfq(); ?>" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
</div>
