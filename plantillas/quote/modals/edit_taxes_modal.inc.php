<div class="modal fade" id="edit-taxes-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="new-item-form" role="form" method="post" action="">
          <input type="hidden" name="id_rfq" value="<?php echo $id_rfq; ?>">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Taxes (%):</label>
                <input type="hidden" name="taxes_original" value="<?= $cotizacion_recuperada->obtener_taxes(); ?>">
                <input type="number" step=".01" name="taxes" id="taxes" class="form-control form-control-sm" value="<?= $cotizacion_recuperada->obtener_taxes(); ?>">
              </div>
              <div class="form-group">
                <label>Profit (%):</label>
                <input type="hidden" name="profit_original" value="<?= $cotizacion_recuperada->obtener_profit(); ?>">
                <input type="number" step=".01" name="profit" id="profit" class="form-control form-control-sm" value="<?= $cotizacion_recuperada->obtener_profit(); ?>">
              </div>
              <div class="form-group">
                <label>Additional general ($):</label>
                <input type="hidden" name="additional_general_original" value="<?= $cotizacion_recuperada->obtener_additional(); ?>">
                <input type="number" step=".01" name="additional_general" id="additional_general" class="form-control form-control-sm" value="<?= $cotizacion_recuperada->obtener_additional(); ?>">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" form="new-item-form"><i class="fa fa-check"></i> Save</button>
      </div>
    </div>
  </div>
</div>