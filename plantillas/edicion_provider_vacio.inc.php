<input type="hidden" name="id_provider" value="<?php echo $id_provider; ?>">
<input type="hidden" name="id_rfq" value="<?php echo $item-> obtener_id_rfq(); ?>">
<div class="card-body">
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="provider">Provider:</label>
                <input type="text" class="form-control" id="provider" name="provider" placeholder="Provider ..." autofocus required value="<?php echo $provider-> obtener_provider(); ?>">
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" step=".01" class="form-control" id="price" name="price" required value="<?php echo $provider-> obtener_price(); ?>">
            </div>
        </div>
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-success" name="guardar_cambios_provider"><i class="fa fa-save"></i> Save</button>
    <a href="<?php echo EDITAR_COTIZACION . '/' . $item-> obtener_id_rfq(); ?>" class="btn btn-danger"><i class="fa fa-ban"></i> Cancel</a>
</div>
