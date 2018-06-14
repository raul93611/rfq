<input type="hidden" name="id_rfq" value="<?php echo $id_rfq; ?>">
<div class="card-body">
    <div class="form-group">
        <label for="brand">Brand:</label>
        <input type="text" class="form-control" id="brand" name="brand" placeholder="Brand ..." autofocus required>
    </div>
    <div class="form-group">
        <label for="part_number">Part #:</label>
        <input type="text" class="form-control" id="part_number" name="part_number" placeholder="Part # ..." required>
    </div>
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control" rows="5" placeholder="Enter description ..." id="description" name="description" required></textarea>
    </div>
    <div class="form-group">
        <label for="quantity">Quantity:</label>
        <input type="number" class="form-control" id="quantity" name="quantity" required>
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary" name="guardar_item">Save</button>
</div>
