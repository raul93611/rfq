<?php
Conexion::abrir_conexion();
$re_quote_subitem = ReQuoteSubitemRepository::get_re_quote_subitem_by_id(Conexion::obtener_conexion(), $id_re_quote_subitem);
$re_quote_item = ReQuoteItemRepository::get_re_quote_item_by_id(Conexion::obtener_conexion(), $re_quote_subitem->get_id_re_quote_item());
$re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $re_quote_item->get_id_re_quote());
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_re_quote_subitem" value="<?= htmlspecialchars($id_re_quote_subitem); ?>">
<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <h2>Project Specifications</h2>
      <div class="form-group">
        <label for="brand_project">Brand:</label>
        <input type="text" class="form-control form-control-sm" name="brand_project" value="<?= htmlspecialchars($re_quote_subitem->get_brand_project()); ?>">
        <input type="hidden" name="brand_project_original" value="<?= htmlspecialchars($re_quote_subitem->get_brand_project()); ?>">
        <small class="form-text text-muted">Enter the brand name associated with the project.</small>
      </div>
      <div class="form-group">
        <label for="part_number_project">Part #:</label>
        <input type="text" class="form-control form-control-sm" name="part_number_project" value="<?= htmlspecialchars($re_quote_subitem->get_part_number_project()); ?>">
        <input type="hidden" name="part_number_project_original" value="<?= htmlspecialchars($re_quote_subitem->get_part_number_project()); ?>">
        <small class="form-text text-muted">Provide the part number for the project.</small>
      </div>
      <div class="form-group">
        <label for="description_project">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" name="description_project"><?= htmlspecialchars($re_quote_subitem->get_description_project()); ?></textarea>
        <input type="hidden" name="description_project_original" value="<?= htmlspecialchars($re_quote_subitem->get_description_project()); ?>">
        <small class="form-text text-muted">Add a detailed description of the project specifications.</small>
      </div>
    </div>
    <div class="col-md-6">
      <h2>E-Logic Proposal</h2>
      <div class="form-group">
        <label for="brand">Brand:</label>
        <input type="text" class="form-control form-control-sm" name="brand" value="<?= htmlspecialchars($re_quote_subitem->get_brand()); ?>">
        <input type="hidden" name="brand_original" value="<?= htmlspecialchars($re_quote_subitem->get_brand()); ?>">
        <small class="form-text text-muted">Enter the brand name for the E-logic proposal.</small>
      </div>
      <div class="form-group">
        <label for="part_number">Part #:</label>
        <input type="text" class="form-control form-control-sm" name="part_number" value="<?= htmlspecialchars($re_quote_subitem->get_part_number()); ?>">
        <input type="hidden" name="part_number_original" value="<?= htmlspecialchars($re_quote_subitem->get_part_number()); ?>">
        <small class="form-text text-muted">Provide the part number for the E-logic proposal.</small>
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control form-control-sm" rows="5" name="description"><?= htmlspecialchars($re_quote_subitem->get_description()); ?></textarea>
        <input type="hidden" name="description_original" value="<?= htmlspecialchars($re_quote_subitem->get_description()); ?>">
        <small class="form-text text-muted">Add a detailed description of the E-logic proposal.</small>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label for="quantity">Quantity:</label>
    <input type="number" class="form-control form-control-sm" name="quantity" value="<?= htmlspecialchars($re_quote_subitem->get_quantity()); ?>">
    <input type="hidden" name="quantity_original" value="<?= htmlspecialchars($re_quote_subitem->get_quantity()); ?>">
    <small class="form-text text-muted">Specify the quantity for the items.</small>
  </div>
  <div class="form-group">
    <label for="comments">Comments:</label>
    <textarea class="form-control form-control-sm" rows="5" name="comments"><?= htmlspecialchars($re_quote_subitem->get_comments()); ?></textarea>
    <input type="hidden" name="comments_original" value="<?= htmlspecialchars($re_quote_subitem->get_comments()); ?>">
    <small class="form-text text-muted">Enter any additional comments or notes.</small>
  </div>
  <div class="form-group">
    <label for="website">Website:</label>
    <input type="text" class="form-control form-control-sm" name="website" value="<?= htmlspecialchars($re_quote_subitem->get_website()); ?>">
    <input type="hidden" name="website_original" value="<?= htmlspecialchars($re_quote_subitem->get_website()); ?>">
    <small class="form-text text-muted">Provide the website link related to this quote.</small>
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-primary" name="save_edit_re_quote_subitem">
    <i class="fa fa-check"></i> Save
  </button>
  <a href="<?= RE_QUOTE . $re_quote->get_id_rfq(); ?>" class="btn btn-secondary">
    <i class="fa fa-times"></i> Cancel
  </a>
</div>