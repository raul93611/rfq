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
              <a href="<?php echo TRACKING_EXCEL . $quote-> obtener_id(); ?>" target="_blank" class="btn btn-success"><i class="fas fa-file-excel"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php
include_once 'plantillas/tracking/modals/edit_tracking_subitem_modal.inc.php';
include_once 'plantillas/tracking/modals/edit_tracking_modal.inc.php';
include_once 'plantillas/tracking/modals/add_tracking_modal.inc.php';
include_once 'plantillas/tracking/modals/add_tracking_subitem_modal.inc.php';
?>
<script src="<?php echo RUTA_JS; ?>tracking.js"></script>
