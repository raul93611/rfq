<input type="hidden" name="id_cuestionario" value="<?php echo $id_cuestionario; ?>">
<div class="card-body">
    <div class="form-group">
        <label for="quantity">Requirement:</label>
        <input type="text" class="form-control" name="requirement" autofocus required>
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-success" name="guardar_out_of_scope"><i class="fa fa-save"></i> Save</button>
    <a href="<?php echo CUESTIONARIO . '/' . $cuestionario-> obtener_id_rfq(); ?>" class="btn btn-danger"><i class="fa fa-ban"></i> Cancel</a>
</div>