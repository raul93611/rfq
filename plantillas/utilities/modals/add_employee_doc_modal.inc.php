<div class="modal fade" id="add_employee_doc_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Employee Doc</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_employee_doc_form" method="post" action="" enctype="multipart/form-data">
          <div class="form-group">
            <label for="doc_type">Document Type</label>
            <select class="form-control" id="doc_type" name="doc_type">
              <option value="administration">Administration</option>
              <option value="brand">Brand</option>
              <option value="letter_of_authorization">Letter of Authorization</option>
              <option value="accounting">Accounting</option>
              <option value="tax_exemption">Tax Exemption</option>
            </select>
          </div>
          <div class="form-group">
            <label for="file_upload">Upload Document</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="file_upload" name="file_upload">
              <label class="custom-file-label" for="file_upload">Choose file</label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" name="save" form="add_employee_doc_form" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>