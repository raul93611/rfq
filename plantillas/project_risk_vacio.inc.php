<input type="hidden" name="id_cuestionario" value="<?php echo $id_cuestionario; ?>">
<div class="card-body">
    <div class="form-group">
        <label for="quantity">Description:</label>
        <input type="text" class="form-control" name="description" autofocus required>
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-success" name="guardar_project_risk"><i class="fa fa-check"></i> Save</button>
    <a href="<?php echo CUESTIONARIO . '/' . $cuestionario-> obtener_id_rfq(); ?>" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
</div>
