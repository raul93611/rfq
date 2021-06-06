<?php
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-2">
          <h1>Tracking table</h1>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-highlighter"></i> Enter the data</h3>
            </div>
            <div class="card-body" id="tracking_box">
              <?php
              TrackingRepository::tracking_list_items($id_rfq);
              ?>
            </div>
            <div class="card-footer footer_item">
              <a class="btn btn-primary" id="go_back" href="<?php echo EDITAR_COTIZACION . '/' . $quote-> obtener_id(); ?>"><i class="fa fa-reply"></i></a>
              <a href="<?php echo TRACKING_PDF . $quote-> obtener_id(); ?>" target="_blank" class="btn btn-primary"><i class="fas fa-file"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<!--******************************************edit TRACKING SUBITEM MODAL**********************************-->
<div class="modal fade" id="edit_tracking_subitem_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add tracking</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <input type="hidden" name="id_rfq" form="edit_tracking_subitem_form" value="<?php echo $quote-> obtener_id(); ?>">
        <form id="edit_tracking_subitem_form" method="post" action="">

        </form>
    </div>
  </div>
</div>
<!--******************************************edit TRACKING MODAL**********************************-->
<div class="modal fade" id="edit_tracking_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add tracking</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <input type="hidden" name="id_rfq" form="edit_tracking_form" value="<?php echo $quote-> obtener_id(); ?>">
        <form id="edit_tracking_form" method="post" action="">

        </form>
    </div>
  </div>
</div>
<!--******************************************ADD TRACKING MODAL**********************************-->
<div class="modal fade" id="new_tracking" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add tracking</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_tracking_form" method="post" enctype="multipart/form-data" action="<?php echo SAVE_TRACKING; ?>">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="quantity_shipped">Quantity(shipped):</label>
                <input type="number" step=".01" class="form-control form-control-sm" name="quantity_shipped" id="quantity_shipped" value="0">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="delivery_date">Delivery date:</label>
                <input type="text" id="delivery_date" class="form-control form-control-sm" name="delivery_date" value="">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="tracking_number">Tracking #:</label>
            <textarea class="form-control form-control-sm" name="tracking_number" rows="5" id="tracking_number"></textarea>
          </div>
          <div class="form-group">
            <label for="signed_by">Signed by:</label>
            <input type="text" id="signed_by" name="signed_by" class="form-control form-control-sm" value="">
          </div>
          <input type="hidden" id="id_item" name="id_item" value="">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="save_tracking" form="add_tracking_form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
<!--******************************************ADD TRACKING SUBITEM MODAL**********************************-->
<div class="modal fade" id="new_tracking_subitem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add tracking</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_tracking_subitem_form" method="post" enctype="multipart/form-data" action="<?php echo SAVE_TRACKING_SUBITEM; ?>">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="quantity_shipped">Quantity(shipped):</label>
                <input type="number" step=".01" class="form-control form-control-sm" name="quantity_shipped" id="quantity_shipped" value="0">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="delivery_date">Delivery date:</label>
                <input type="text" id="delivery_date_subitem" class="form-control form-control-sm" name="delivery_date" value="">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="tracking_number">Tracking #:</label>
            <textarea class="form-control form-control-sm" name="tracking_number" rows="5" id="tracking_number"></textarea>
          </div>
          <div class="form-group">
            <label for="signed_by">Signed by:</label>
            <input type="text" id="signed_by" name="signed_by" class="form-control form-control-sm" value="">
          </div>
          <input type="hidden" id="id_subitem" name="id_subitem" value="">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="save_tracking_subitem" form="add_tracking_subitem_form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo RUTA_JS; ?>tracking.js"></script>
