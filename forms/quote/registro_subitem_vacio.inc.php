<input type="hidden" name="id_item" value="<?= $id_item; ?>">
<div class="card-body user-form">

  <div class="item-comparison-grid">
    <div class="item-panel item-panel--project">
      <div class="item-panel-header"><i class="fas fa-file-alt mr-2"></i> Project Specifications</div>
      <div class="item-panel-body">
        <div class="form-group">
          <label for="brand_project">Brand</label>
          <input type="text" class="form-control form-control-sm" id="brand_project" name="brand_project" placeholder="Brand ..." autofocus>
        </div>
        <div class="form-group">
          <label for="part_number_project">Part #</label>
          <input type="text" class="form-control form-control-sm" id="part_number_project" name="part_number_project" placeholder="Part number ...">
        </div>
        <div class="form-group mb-0">
          <label for="description_project">Description</label>
          <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description_project" name="description_project"></textarea>
        </div>
      </div>
    </div>
    <div class="item-panel item-panel--proposal">
      <div class="item-panel-header"><i class="fas fa-lightbulb mr-2"></i> E-logic Proposal</div>
      <div class="item-panel-body">
        <div class="form-group">
          <label for="brand">Brand</label>
          <input type="text" class="form-control form-control-sm" id="brand" name="brand" placeholder="Brand ...">
        </div>
        <div class="form-group">
          <label for="part_number">Part #</label>
          <input type="text" class="form-control form-control-sm" id="part_number" name="part_number" placeholder="Part number ...">
        </div>
        <div class="form-group mb-0">
          <label for="description">Description</label>
          <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description" name="description"></textarea>
        </div>
      </div>
    </div>
  </div>

  <div class="item-extra-fields">
    <div class="form-row">
      <div class="form-group col-md-4">
        <label for="quantity">Quantity</label>
        <input type="number" class="form-control form-control-sm" id="quantity" name="quantity" min="0">
      </div>
      <div class="form-group col-md-8">
        <label for="website">Website</label>
        <input type="text" class="form-control form-control-sm" id="website" name="website" placeholder="https://...">
      </div>
    </div>
    <div class="form-group mb-0">
      <label for="comments">Comments</label>
      <textarea class="summernote_textarea form-control form-control-sm" rows="4" placeholder="Additional comments ..." id="comments" name="comments"></textarea>
    </div>
  </div>

</div>
<div class="card-footer d-flex justify-content-end" style="gap:8px;">
  <button type="submit" class="btn btn-primary btn-sm" name="guardar_subitem">
    <i class="fa fa-check mr-1"></i> Save Subitem
  </button>
  <a href="<?= EDITAR_COTIZACION . '/' . $_hdr_item->obtener_id_rfq(); ?>" class="btn btn-secondary btn-sm">
    <i class="fa fa-times mr-1"></i> Cancel
  </a>
</div>