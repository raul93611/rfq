<div class="modal fade" id="edit_fulfillment_item_modal" tabindex="-1" role="dialog" aria-labelledby="editFulfillmentItemModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editFulfillmentItemModalLabel">Edit Fulfillment Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" form="edit_fulfillment_item_form" name="id_rfq" value="<?= htmlspecialchars($quote->obtener_id()); ?>">
      <form id="edit_fulfillment_item_form" method="post" action="">
      </form>
    </div>
  </div>
</div>