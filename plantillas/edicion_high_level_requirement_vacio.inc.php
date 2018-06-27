<input type="hidden" name="id_cuestionario" value="<?php echo $high_level_requirement-> obtener_id_cuestionario(); ?>">
<input type="hidden" name="id_high_level_requirement" value="<?php echo $id_high_level_requirement; ?>">
<div class="card-body">
    <div class="form-group">
        <label for="quantity">Requirement:</label>
        <input type="text" class="form-control" name="requirement" required value="<?php echo $high_level_requirement-> obtener_requirement(); ?>">
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary" name="editar_high_level_requirement">Save</button>
</div>

