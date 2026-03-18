<?php
Conexion::abrir_conexion();
$re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $id_re_quote);
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_re_quote" value="<?= $id_re_quote; ?>">
<div class="card-body user-form">
  <div class="item-comparison-grid">
    <div class="item-panel item-panel--project">
      <div class="item-panel-header"><i class="fas fa-file-alt mr-2"></i> Project Specifications</div>
      <div class="item-panel-body">
        <div class="form-group">
          <label>Brand</label>
          <input type="text" class="form-control form-control-sm" name="brand_project" placeholder="Brand ..." autofocus>
        </div>
        <div class="form-group">
          <label>Part #</label>
          <input type="text" class="form-control form-control-sm" name="part_number_project" placeholder="Part number ...">
        </div>
        <div class="form-group mb-0">
          <label>Description</label>
          <textarea class="form-control form-control-sm" rows="5" name="description_project" placeholder="Enter description ..."></textarea>
        </div>
      </div>
    </div>
    <div class="item-panel item-panel--proposal">
      <div class="item-panel-header"><i class="fas fa-lightbulb mr-2"></i> E-Logic Proposal</div>
      <div class="item-panel-body">
        <div class="form-group">
          <label>Brand</label>
          <input type="text" class="form-control form-control-sm" name="brand" placeholder="Brand ...">
        </div>
        <div class="form-group">
          <label>Part #</label>
          <input type="text" class="form-control form-control-sm" name="part_number" placeholder="Part number ...">
        </div>
        <div class="form-group mb-0">
          <label>Description</label>
          <textarea class="form-control form-control-sm" rows="5" name="description" placeholder="Enter description ..."></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="item-extra-fields">
    <div class="form-row">
      <div class="form-group col-md-4">
        <label>Quantity</label>
        <input type="number" class="form-control form-control-sm" name="quantity">
      </div>
      <div class="form-group col-md-8">
        <label>Website</label>
        <input type="text" class="form-control form-control-sm" name="website" placeholder="https://...">
      </div>
    </div>
    <div class="form-group mb-0">
      <label>Comments</label>
      <textarea class="summernote_textarea form-control form-control-sm" rows="4" name="comments" placeholder="Additional comments ..."></textarea>
    </div>
  </div>
</div>
<div class="card-footer d-flex justify-content-between align-items-center" style="gap:8px;">
  <a href="<?= RE_QUOTE . $re_quote->get_id_rfq(); ?>" class="btn btn-secondary btn-sm">
    <i class="fas fa-arrow-left mr-1"></i> Back to Re-Quote
  </a>
  <button type="submit" class="btn btn-primary btn-sm" name="save_re_quote_item">
    <i class="fa fa-check mr-1"></i> Save
  </button>
</div>
