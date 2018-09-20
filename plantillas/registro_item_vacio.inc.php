<input type="hidden" name="id_rfq" value="<?php echo $id_rfq; ?>">
<div class="card-body">
    <div class="row">
        <div class="col">
            <h2>Project specifications</h2>
            <div class="form-group">
                <label for="brand_project">Brand:</label>
                <input type="text" class="form-control form-control-sm" id="brand_project" name="brand_project" placeholder="Brand ..." autofocus>
            </div>
            <div class="form-group">
                <label for="part_number_project">Part #:</label>
                <input type="text" class="form-control form-control-sm" id="part_number_project" name="part_number_project" placeholder="Part # ...">
            </div>
            <div class="form-group">
                <label for="description_project">Description:</label>
                <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description_project" name="description_project" ></textarea>
            </div>
        </div>
        <div class="col">
            <h2>E-logic proposal</h2>
            <div class="form-group">
                <label for="brand">Brand:</label>
                <input type="text" class="form-control form-control-sm" id="brand" name="brand" placeholder="Brand ..." autofocus >
            </div>
            <div class="form-group">
                <label for="part_number">Part #:</label>
                <input type="text" class="form-control form-control-sm" id="part_number" name="part_number" placeholder="Part # ..." >
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description" name="description" ></textarea>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="quantity">Quantity:</label>
        <input type="number" class="form-control form-control-sm" id="quantity" name="quantity">
    </div>
    <div class="form-group">
        <label for="comments">Comments:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter comments ..." id="comments" name="comments"></textarea>
    </div>
    <div class="form-group">
        <label for="website">Website:</label>
        <input type="url" class="form-control form-control-sm" id="website" name="website" placeholder="Website ...">
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-success" name="guardar_item"><i class="fa fa-check"></i> Save</button>
    <a href="<?php echo EDITAR_COTIZACION . '/' . $id_rfq; ?>" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
</div>
