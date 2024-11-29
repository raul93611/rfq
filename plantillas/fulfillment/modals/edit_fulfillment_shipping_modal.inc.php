<?php
$shippings = explode('|', $quote->obtener_fulfillment_shipping() ?? '');
?>
<div class="modal fade" id="edit_fulfillment_shipping_modal" tabindex="-1" role="dialog" aria-labelledby="editFulfillmentShippingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editFulfillmentShippingModalLabel">Edit Fulfillment Shipping</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" form="edit_fulfillment_shipping_form" name="id_rfq" value="<?= htmlspecialchars($quote->obtener_id()); ?>">
      <form id="edit_fulfillment_shipping_form" method="post" data-counter="<?= count($shippings) - 1; ?>" action="">
      </form>
    </div>
  </div>
</div>