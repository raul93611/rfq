<?php
$shippings = explode('|', $quote-> obtener_fulfillment_shipping());
?>
<div class="modal fade" id="edit_fulfillment_shipping_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <input type="hidden" name="id_rfq" form="edit_fulfillment_shipping_form" value="<?php echo $quote-> obtener_id(); ?>">
        <form id="edit_fulfillment_shipping_form" method="post" data="<?php echo count($shippings) - 1; ?>" action="">

        </form>
    </div>
  </div>
</div>
