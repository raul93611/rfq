<?php
Database::open_connection();
$item = ItemRepository::get_by_id(Database::get_connection(), $subitem-> get_id_item());
$quote = QuoteRepository::get_by_id(Database::get_connection(), $item-> get_id_quote());
Database::close_connection();
?>
<input type="hidden" name="id_subitem" value="<?php echo $id_subitem; ?>">
<input type="hidden" name="id_quote" value="<?php echo $item->get_id_quote(); ?>">
<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <h2>Project specifications</h2>
      <div class="form-group">
        <label for="brand_project">Brand:</label>
        <input type="text" class="form-control form-control-sm" id="brand_project" name="brand_project" placeholder="Brand ..." autofocus value="<?php echo $subitem->get_brand_project(); ?>">
        <input type="hidden" name="brand_project_original" value="<?php echo $subitem->get_brand_project(); ?>">
      </div>
      <div class="form-group">
        <label for="part_number_project">Part #:</label>
        <input type="text" class="form-control form-control-sm" id="part_number_project" name="part_number_project" placeholder="Part # ..." value="<?php echo $subitem->get_part_number_project(); ?>">
        <input type="hidden" name="part_number_project_original" value="<?php echo $subitem->get_part_number_project(); ?>">
      </div>
      <div class="form-group">
        <label for="description_project">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description_project" name="description_project"><?php echo $subitem->get_description_project(); ?></textarea>
        <input type="hidden" name="description_project_original" value="<?php echo $subitem->get_description_project(); ?>">
      </div>
    </div>
    <div class="col-md-6">
      <h2>E-logic proposal</h2>
      <div class="form-group">
        <label for="brand">Brand:</label>
        <input type="text" class="form-control form-control-sm" id="brand" name="brand" placeholder="Brand ..." autofocus value="<?php echo $subitem->get_brand(); ?>">
        <input type="hidden" name="brand_original" value="<?php echo $subitem->get_brand(); ?>">
      </div>
      <div class="form-group">
        <label for="part_number">Part #:</label>
        <input type="text" class="form-control form-control-sm" id="part_number" name="part_number" placeholder="Part # ..." value="<?php echo $subitem->get_part_number(); ?>">
        <input type="hidden" name="part_number_original" value="<?php echo $subitem->get_part_number(); ?>">
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description" name="description"><?php echo $subitem-> get_description(); ?></textarea>
        <input type="hidden" name="description_original" value="<?php echo $subitem-> get_description(); ?>">
      </div>
    </div>
  </div>
  <div class="form-group">
    <label for="quantity">Quantity:</label>
    <input type="number" class="form-control form-control-sm" id="quantity" name="quantity" required value="<?php echo $subitem->get_quantity(); ?>">
    <input type="hidden" name="quantity_original" value="<?php echo $subitem->get_quantity(); ?>">
  </div>
  <div class="form-group">
    <label for="comments">Comments:</label>
    <textarea class="form-control form-control-sm" rows="5" placeholder="Enter comments ..." id="comments" name="comments"><?php echo $subitem->get_comments(); ?></textarea>
    <input type="hidden" name="comments_original" value="<?php echo $subitem->get_comments(); ?>">
  </div>
  <div class="form-group">
    <label for="website">Website:</label>
    <input type="text" class="form-control form-control-sm" id="website" name="website" placeholder="Website ..." value="<?php echo $subitem->get_website(); ?>">
    <input type="hidden" name="website_original" value="<?php echo $subitem->get_website(); ?>">
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="guardar_cambios_subitem"><i class="fa fa-check"></i> Save</button>
  <a href="<?php echo EDIT_QUOTE . '/' . $item-> get_id_quote(); ?>" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
</div>
