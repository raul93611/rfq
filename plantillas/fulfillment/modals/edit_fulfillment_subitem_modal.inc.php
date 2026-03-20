<div class="modal fade" id="edit_fulfillment_subitem_modal" tabindex="-1" role="dialog" aria-labelledby="editFulfillmentSubitemModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="background:var(--color-dark);color:#fff;border:none;">
        <h5 class="modal-title"><i class="fas fa-edit mr-2"></i> Edit Fulfillment Subitem</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:0.7;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" name="id_rfq" form="edit_fulfillment_subitem_form" value="<?= htmlspecialchars($quote->obtener_id()); ?>">
      <form id="edit_fulfillment_subitem_form" method="post" action="">
      </form>
    </div>
  </div>
</div>