<input type="hidden" name="id_provider" value="<?php echo $id_provider; ?>">
<input type="hidden" name="id_quote" value="<?php echo $item-> get_id_quote(); ?>">
<?php
Database::open_connection();
$quote = QuoteRepository::get_by_id(Database::get_connection(), $item-> get_id_quote());
Database::close_connection();
?>
<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="provider">Provider:</label>
        <input type="text" class="form-control form-control-sm" id="provider" name="provider" placeholder="Provider ..." autofocus required value="<?php echo $provider-> get_provider(); ?>">
        <input type="hidden" name="provider_original" value="<?php echo $provider-> get_provider(); ?>">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" step=".01" class="form-control form-control-sm" id="price" name="price" required value="<?php echo $provider-> get_price(); ?>">
        <input type="hidden" name="price_original" value="<?php echo $provider-> get_price(); ?>">
      </div>
    </div>
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="guardar_cambios_provider"><i class="fa fa-check"></i> Save</button>
  <a href="<?php echo EDIT_QUOTE . '/' . $item-> get_id_quote(); ?>" class="btn btn-info"><i class="fa fa-times"></i> Cancel</a>
  <a href="<?php echo DELETE_PROVIDER . '/' . $id_provider; ?>" class="delete_provider_item_button btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
</div>
