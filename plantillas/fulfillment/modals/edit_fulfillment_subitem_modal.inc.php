<div class="modal fade" id="edit_fulfillment_subitem_modal" tabindex="-1" role="dialog" aria-labelledby="editFulfillmentSubitemModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editFulfillmentSubitemModalLabel">Edit Fulfillment Subitem</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" name="id_rfq" form="edit_fulfillment_subitem_form" value="<?= htmlspecialchars($quote->obtener_id()); ?>">
      <form id="edit_fulfillment_subitem_form" method="post" action="">
      </form>
    </div>
  </div>
</div>