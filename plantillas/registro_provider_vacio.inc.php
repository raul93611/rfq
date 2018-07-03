<input type="hidden" name="id_item" value="<?php echo $id_item; ?>">
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
    <button type="submit" class="btn btn-primary" name="guardar_provider">Save</button>
</div>

