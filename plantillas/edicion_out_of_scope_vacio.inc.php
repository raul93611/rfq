<input type="hidden" name="id_cuestionario" value="<?php echo $out_of_scope-> obtener_id_cuestionario(); ?>">
<input type="hidden" name="id_out_of_scope" value="<?php echo $id_out_of_scope; ?>">
<div class="card-body">
    <div class="form-group">
        <label>Requirement:</label>
        <input type="text" class="form-control" name="requirement" required value="<?php echo $out_of_scope-> obtener_requirement(); ?>">
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary" name="editar_out_of_scope">Save</button>
</div>


