<div class="modal fade" id="link_quote_modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Set Master</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?= LINK_QUOTE; ?>" id="link_quote_form" method="post">
          <div class="form-group">
            <label for="quote_ids">Proposal:</label><br>
            <select style="width: 100%" id="quote_ids" name="master">
              <option>Select a proposal ID</option>
            </select>
            <input type="hidden" name="id_rfq" value="<?= $id_rfq; ?>">
          </div>
          <p><b>Note:</b> Type at least 4 characters.</p>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="submit" form="link_quote_form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
      </div>
    </div>
  </div>
</div>