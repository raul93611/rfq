<input type="hidden" name="id_cuestionario" value="<?php echo $project_risk-> obtener_id_cuestionario(); ?>">
<input type="hidden" name="id_project_risk" value="<?php echo $id_project_risk; ?>">
<div class="card-body">
    <div class="form-group">
        <label>Description:</label>
        <input type="text" class="form-control" name="description" required value="<?php echo $project_risk-> obtener_description(); ?>">
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary" name="editar_project_risk">Save</button>
</div>



