<input type="hidden" name="id_cuestionario" value="<?php echo $project_milestone-> obtener_id_cuestionario(); ?>">
<input type="hidden" name="id_project_milestone" value="<?php echo $id_project_milestone; ?>">
<?php
Conexion::abrir_conexion();
$cuestionario = RepositorioCuestionario::obtener_cuestionario_por_id(Conexion::obtener_conexion(), $project_milestone-> obtener_id_cuestionario());
Conexion::cerrar_conexion();
 ?>
<div class="card-body">
    <div class="form-group">
        <label>Date:</label>
        <input type="text" class="form-control" id="date_milestone" name="date_milestone" autofocus required value="<?php echo $project_milestone-> obtener_date_milestone(); ?>">
    </div>
    <div class="form-group">
        <label>Description:</label>
        <input type="text" class="form-control" name="description" required value="<?php echo $project_milestone-> obtener_description(); ?>">
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-success" name="editar_project_milestone"><i class="fa fa-save"></i> Save</button>
    <a href="<?php echo CUESTIONARIO . '/' . $cuestionario-> obtener_id_rfq(); ?>" class="btn btn-danger"><i class="fa fa-ban"></i> Cancel</a>
</div>