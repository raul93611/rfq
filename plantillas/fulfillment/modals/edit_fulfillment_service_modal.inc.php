<div class="modal fade" id="edit_fulfillment_service_modal" tabindex="-1" role="dialog" aria-labelledby="editFulfillmentServiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editFulfillmentServiceModalLabel">Edit Fulfillment Service</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" name="id_rfq" form="edit_fulfillment_service_form" value="<?= htmlspecialchars($quote->obtener_id()); ?>">
      <form id="edit_fulfillment_service_form" method="post" action="">
      </form>
    </div>
  </div>
</div>