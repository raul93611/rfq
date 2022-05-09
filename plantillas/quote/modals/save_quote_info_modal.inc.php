<div class="modal fade" id="quote_info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Quote Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="quote_info_form" method="post" enctype="multipart/form-data" action="<?php echo SAVE_QUOTE_INFO; ?>">
          <?php include_once 'forms/quote/quote_info.inc.php'; ?>
          <input type="hidden" name="id_rfq" value="<?php echo $cotizacion_recuperada-> obtener_id(); ?>">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="save_quote_info" form="quote_info_form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
