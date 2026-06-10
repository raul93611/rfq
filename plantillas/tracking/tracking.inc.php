<?php
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
$items = RepositorioItem::obtener_items_por_id_rfq($conexion, $id_rfq);
Conexion::cerrar_conexion();
?>
<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Tracking</h1>
      <p class="page-subtitle">Shipment tracking for this quote</p>
    </div>
    <div style="display:flex;gap:8px;align-items:center;">
      <a href="<?= TRACKING_PDF . htmlspecialchars($quote->obtener_id()); ?>" target="_blank" class="btn btn-secondary btn-sm">
        <i class="fas fa-file mr-1"></i> PDF
      </a>
      <a href="<?= TRACKING_EXCEL . htmlspecialchars($quote->obtener_id()); ?>" target="_blank" class="btn btn-secondary btn-sm">
        <i class="fas fa-file-excel mr-1"></i> Excel
      </a>
      <a class="btn btn-primary btn-sm" href="<?= EDITAR_COTIZACION . '/' . htmlspecialchars($quote->obtener_id()); ?>">
        <i class="fa fa-reply mr-1"></i> Back
      </a>
    </div>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="card chart-card">
        <div class="card-body p-0">
          <div id="tracking_box">
            <?php TrackingRepository::tracking_list_items($id_rfq); ?>
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
<script src="<?= asset_url('js/tracking.js'); ?>"></script>
