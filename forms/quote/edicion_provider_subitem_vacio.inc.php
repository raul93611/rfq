<?php
Database::open_connection();
$provider_subitem = ProviderSubitemRepository::get_by_id(Database::get_connection(),$id_provider_subitem);
$item = ItemRepository::get_by_id(Database::get_connection(), $subitem-> get_id_item());
$quote = QuoteRepository::get_by_id(Database::get_connection(), $item-> get_id_quote());
Database::close_connection();
?>
<input type="hidden" name="id_provider_subitem" value="<?php echo $id_provider_subitem; ?>">
<input type="hidden" name="id_quote" value="<?php echo $item-> get_id_quote(); ?>">

<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="provider">Provider:</label>
        <input type="text" class="form-control form-control-sm" id="provider" name="provider" placeholder="Provider ..." autofocus required value="<?php echo $provider_subitem-> get_provider(); ?>">
        <input type="hidden" name="provider_original" value="<?php echo $provider_subitem-> get_provider(); ?>">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" step=".01" class="form-control form-control-sm" id="price" name="price" required value="<?php echo $provider_subitem-> get_price(); ?>">
        <input type="hidden" name="price_original" value="<?php echo $provider_subitem-> get_price(); ?>">
      </div>
    </div>
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="guardar_cambios_provider_subitem"><i class="fa fa-check"></i> Save</button>
  <a href="<?php echo EDIT_QUOTE . '/' . $item-> get_id_quote(); ?>" class="btn btn-info"><i class="fa fa-times"></i> Cancel</a>
  <a href="<?php echo DELETE_PROVIDER_SUBITEM . '/' . $id_provider_subitem; ?>" class="delete_provider_subitem_button btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
</div>
