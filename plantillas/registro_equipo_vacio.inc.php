<input type="hidden" name="id_rfq" value="<?php echo $id_rfq; ?>">
<div class="card-body">
    <div class="form-group">
        <label for="brand">Brand:</label>
        <input type="text" class="form-control" id="brand" name="brand" placeholder="Enter brand ..." autofocus>
    </div>
    <div class="form-group">
        <label for="part_number">Part #:</label>
        <input type="text" class="form-control" id="part_number" name="part_number" placeholder="Enter part # ...">
    </div>
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control" rows="5" placeholder="Enter description ..." id="description" name="description"></textarea>
    </div>
    <div class="form-group">
        <label for="quantity">Quantity:</label>
        <input type="number" class="form-control" id="quantity" name="quantity">
    </div>
    <div class="form-group">
        <label for="unit_price">Unit price:</label>
        <input type="number" class="form-control" id="unit_price" name="unit_price">
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary" name="guardar_equipo">Save</button>
    <button type="submit" class="btn btn-primary float-right" name="cancelar">Cancel</button>
</div>
