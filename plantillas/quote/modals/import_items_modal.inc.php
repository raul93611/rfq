<div class="modal fade" id="import-items-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Import Items</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="import-items-form" action="<?= IMPORT_ITEMS; ?>" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="file_upload">Upload Document</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="uploaded_file" name="uploaded_file" accept=".csv,.xls,.xlsx">
              <label class="custom-file-label" for="uploaded_file">Choose file</label>
            </div>
          </div>
          <input type="hidden" name="id_rfq" value="<?= htmlspecialchars($cotizacion_recuperada->obtener_id()); ?>">
          <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($_SESSION['user']->obtener_id()); ?>">
        </form>
      </div>
      <div class="modal-footer">
        <button form="import-items-form" class="btn btn-success" type="submit">Upload File</button>
        </button>
      </div>
    </div>
  </div>
</div>