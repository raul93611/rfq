<div class="modal fade" id="add-subitem-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Subitem</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-subitem-form" role="form" method="post" action="">
          <input type="hidden" name="id_item" value="">
          <div class="row">
            <div class="col-md-6">
              <h3>Project specifications</h3>
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
                <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description_project" name="description_project"></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <h3>E-logic proposal</h3>
              <div class="form-group">
                <label for="brand">Brand:</label>
                <input type="text" class="form-control form-control-sm" id="brand" name="brand" placeholder="Brand ..." autofocus>
              </div>
              <div class="form-group">
                <label for="part_number">Part #:</label>
                <input type="text" class="form-control form-control-sm" id="part_number" name="part_number" placeholder="Part # ...">
              </div>
              <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control form-control-sm" rows="5" placeholder="Enter description ..." id="description" name="description"></textarea>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" class="form-control form-control-sm" id="quantity" name="quantity">
          </div>
          <div class="form-group">
            <label for="comments">Comments:</label>
            <textarea class="summernote_textarea form-control form-control-sm" rows="5" placeholder="Enter comments ..." id="comments" name="comments"></textarea>
          </div>
          <div class="form-group">
            <label for="website">Website:</label>
            <input type="text" class="form-control form-control-sm" id="website" name="website" placeholder="Website ...">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" form="add-subitem-form"><i class="fa fa-check"></i> Save</button>
      </div>
    </div>
  </div>
</div>