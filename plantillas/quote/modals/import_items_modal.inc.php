<div class="modal fade" id="import-items-modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="background:var(--color-dark);border-bottom:none;padding:16px 20px;">
        <h5 class="modal-title" style="color:#fff;font-family:'Manrope',sans-serif;font-size:15px;font-weight:700;">
          <i class="fas fa-file-import mr-2"></i> Import Items
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:0.7;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body user-form" style="padding:20px;">
        <form id="import-items-form" action="<?= IMPORT_ITEMS; ?>" method="post" enctype="multipart/form-data">
          <div class="form-group mb-0">
            <label for="uploaded_file">File</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="uploaded_file" name="uploaded_file" accept=".csv,.xls,.xlsx">
              <label class="custom-file-label" for="uploaded_file">Choose file (.csv, .xls, .xlsx)</label>
            </div>
          </div>
          <input type="hidden" name="id_rfq" value="<?= htmlspecialchars($cotizacion_recuperada->obtener_id()); ?>">
          <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($_SESSION['user']->obtener_id()); ?>">
        </form>
      </div>
      <div class="modal-footer" style="border-top:1px solid #f0f2f5;padding:12px 20px;justify-content:flex-end;gap:8px;">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          <i class="fa fa-times mr-1"></i> Cancel
        </button>
        <button form="import-items-form" class="btn btn-primary btn-sm" type="submit">
          <i class="fas fa-upload mr-1"></i> Upload File
        </button>
      </div>
    </div>
  </div>
</div>