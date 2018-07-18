<input type="hidden" name="id_item" value="<?php echo $id_item; ?>">
<?php
Conexion::abrir_conexion();
$item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $id_item);
Conexion::cerrar_conexion();
?>
<div class="card-body">
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="provider">Provider:</label>
                <input type="text" class="form-control" id="provider" name="provider" placeholder="Provider ..." autofocus required>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" step=".01" class="form-control" id="price" name="price" required>
            </div>
        </div>
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-success" name="guardar_provider"><i class="fa fa-check"></i> Save</button>
    <a href="<?php echo EDITAR_COTIZACION . '/' . $item-> obtener_id_rfq(); ?>" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
</div>
