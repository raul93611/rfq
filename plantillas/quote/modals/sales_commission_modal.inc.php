<?php
Conexion::abrir_conexion();
$sales_commissions = SalesCommissionRepository::get_all(Conexion::obtener_conexion());
$isReQuoteCreated = ReQuoteRepository::re_quote_exists(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
if ($isReQuoteCreated) {
  $sales_commissions_amounts = [
    '$0',
    '$' . number_format($cotizacion_recuperada->obtener_re_quote_profit(), 2) . '/' .
      number_format($cotizacion_recuperada->obtener_re_quote_profit_percentage(), 2) . '%',
    '$' . number_format($cotizacion_recuperada->obtener_real_fulfillment_profit(), 2) . '/' . number_format($cotizacion_recuperada->obtener_real_fulfillment_profit_percentage(), 2) . '%'
  ];
?>
  <div class="modal fade" id="sales_commission_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Sales Commission</h5>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <select class="form-control form-control-sm" name="sales_commission" form="form_edited_quote">
              <?php
              foreach ($sales_commissions as $key => $sales_commission) {
              ?>
                <option value="<?php echo $sales_commission->get_sales_commission(); ?>">
                  <?php
                  echo $sales_commission->get_sales_commission() . '(' . $sales_commissions_amounts[$key] . ')';
                  ?>
                </option>
              <?php
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="sales_commission_comment">Comment:</label>
            <textarea class="form-control form-control-sm" name="sales_commission_comment" id="sales_commission_comment" rows="5" form="form_edited_quote"></textarea>
          </div>
          <p><b>Note:</b> Reload the page to dismiss the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="submit" name="guardar_cambios_cotizacion" form="form_edited_quote" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        </div>
      </div>
    </div>
  </div>
<?php
}
?>