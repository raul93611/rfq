<input type="hidden" name="id_cuestionario" value="<?php echo $project_milestone-> obtener_id_cuestionario(); ?>">
<input type="hidden" name="id_project_milestone" value="<?php echo $id_project_milestone; ?>">
<div class="card-body">
    <div class="form-group">
        <label>Date:</label>
        <input type="text" class="form-control" id="date_milestone" name="date_milestone" required value="<?php echo $project_milestone-> obtener_date_milestone(); ?>">
    </div>
    <div class="form-group">
        <label>Description:</label>
        <input type="text" class="form-control" name="description" required value="<?php echo $project_milestone-> obtener_description(); ?>">
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary" name="editar_project_milestone">Save</button>
</div>




