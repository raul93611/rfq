<?php
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
$items = RepositorioItem::obtener_items_por_id_rfq($conexion, $id_rfq);
Conexion::cerrar_conexion();
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-2">
          <h1>Tracking Table</h1>
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
              <h3 class="card-title"><i class="fas fa-highlighter"></i> Enter the Data</h3>
            </div>
            <div class="card-body" id="tracking_box">
              <?php TrackingRepository::tracking_list_items($id_rfq); ?>
            </div>
            <div class="card-footer footer_item">
              <?php $quote_id = htmlspecialchars($quote->obtener_id()); ?>
              <a class="btn btn-secondary" id="go_back" href="<?= EDITAR_COTIZACION . '/' . $quote_id; ?>"><i class="fa fa-reply"></i></a>
              <a href="<?= TRACKING_PDF . $quote_id; ?>" target="_blank" class="btn btn-primary"><i class="fas fa-file"></i></a>
              <a href="<?= TRACKING_EXCEL . $quote_id; ?>" target="_blank" class="btn btn-primary"><i class="fas fa-file-excel"></i></a>
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
<script src="<?= RUTA_JS; ?>tracking.js"></script>