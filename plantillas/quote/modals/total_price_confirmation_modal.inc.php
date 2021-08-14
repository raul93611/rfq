<div class="modal fade" id="total_price_confirmation_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Total Price Confirmation</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="total_price_confirmation">Total price:</label>
          <input type="number" form="form_edited_quote" step=".01" class="form-control form-control-sm" id="total_price_confirmation" name="total_price_confirmation" autofocus value="">
        </div>
        <div class="custom-control custom-checkbox">
          <input type="checkbox" form="form_edited_quote" id="multi_year_project" class="custom-control-input" name="multi_year_project" value="1">
          <label class="custom-control-label" for="multi_year_project">Multi-year project</label>
        </div>
        <br>
        <p>Note: Reload the page to dismiss the modal</p>
      </div>
      <div class="modal-footer">
        <button type="submit" form="form_edited_quote" class="btn btn-success" name="guardar_cambios_cotizacion"><i class="fa fa-check"></i> Save</button>
      </div>
    </div>
  </div>
</div>
