<input type="hidden" name="id_cuestionario" value="<?php echo $id_cuestionario; ?>">
<?php
Conexion::abrir_conexion();
$cuestionario = RepositorioCuestionario::obtener_cuestionario_por_id(Conexion::obtener_conexion(), $id_cuestionario);
Conexion::cerrar_conexion();
 ?>
<div class="card-body">
    <div class="form-group">
        <label for="quantity">Requirement:</label>
        <input type="text" class="form-control" name="requirement" autofocus required>
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-success" name="guardar_high_level_requirement"><i class="fa fa-save"></i> Save</button>
    <a href="<?php echo CUESTIONARIO . '/' . $cuestionario-> obtener_id_rfq(); ?>" class="btn btn-danger"><i class="fa fa-ban"></i> Cancel</a>
</div>
