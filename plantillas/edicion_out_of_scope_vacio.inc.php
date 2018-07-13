<input type="hidden" name="id_cuestionario" value="<?php echo $out_of_scope-> obtener_id_cuestionario(); ?>">
<input type="hidden" name="id_out_of_scope" value="<?php echo $id_out_of_scope; ?>">
<?php
Conexion::abrir_conexion();
$cuestionario = RepositorioCuestionario::obtener_cuestionario_por_id(Conexion::obtener_conexion(), $out_of_scope-> obtener_id_cuestionario());
Conexion::cerrar_conexion();
 ?>
<div class="card-body">
    <div class="form-group">
        <label>Requirement:</label>
        <input type="text" class="form-control" name="requirement" autofocus required value="<?php echo $out_of_scope-> obtener_requirement(); ?>">
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-success" name="editar_out_of_scope"><i class="fa fa-save"></i> Save</button>
    <a href="<?php echo CUESTIONARIO . '/' . $cuestionario-> obtener_id_rfq(); ?>" class="btn btn-danger"><i class="fa fa-ban"></i> Cancel</a>
</div>
