<div class="modal fade" id="new-item-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="new-item-form" role="form" method="post" action="">
          <?php
          include_once 'forms/quote/registro_item_vacio.inc.php';
          ?>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" form="new-item-form"><i class="fa fa-check"></i> Save</button>
      </div>
    </div>
  </div>
</div>