<div class="modal fade" id="add_employee_doc_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:var(--color-dark);color:#fff;border-bottom:none;">
        <h5 class="modal-title"><i class="fas fa-file-upload mr-2"></i>Add Document</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:0.7;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body user-form">
        <form id="add_employee_doc_form" method="post" action="" enctype="multipart/form-data">
          <div class="form-group">
            <label for="doc_type">Document Type</label>
            <select class="form-control" id="doc_type" name="doc_type">
              <option value="administration">Administration</option>
              <option value="brand">Branding</option>
              <option value="letter_of_authorization">Letter of Authorization</option>
              <option value="accounting">Accounting</option>
              <option value="tax_exemption">Tax Exemption</option>
              <option value="policies">Policies</option>
              <option value="company_compliance_documents">Company Compliance Documents</option>
            </select>
          </div>
          <div class="form-group">
            <label for="file_upload">File</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="file_upload" name="file_upload">
              <label class="custom-file-label" for="file_upload">Choose file</label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer" style="justify-content:flex-end;">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
        <button type="submit" name="save" form="add_employee_doc_form" class="btn btn-primary btn-sm">
          <i class="fas fa-upload mr-1"></i> Upload
        </button>
      </div>
    </div>
  </div>
</div>
