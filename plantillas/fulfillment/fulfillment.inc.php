<?php
// Open database connection
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

// Retrieve necessary data
$quote = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
$providers_list = ProviderListRepository::get_all($conexion);
$items_exists = RepositorioItem::items_exists($conexion, $id_rfq);
$total_services = ServiceRepository::get_total($conexion, $id_rfq);
$payment_terms = PaymentTermRepository::get_all($conexion);
$invoices = InvoiceRepository::get_all_by_id_rfq($conexion, $id_rfq);

// Close the database connection
Conexion::cerrar_conexion();
?>

<div class="content-wrapper">
  <input type="hidden" id="id-rfq" value="<?= htmlspecialchars($id_rfq, ENT_QUOTES, 'UTF-8') ?>">

  <!-- Content Header -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-3">
          <h1>Fulfillment Table</h1>
        </div>
        <div class="col-sm-5 text-center">
          <?php
          include_once 'plantillas/fulfillment/templates/comments_button.inc.php';
          include_once 'plantillas/fulfillment/templates/pending_button.inc.php';
          ?>
          <button type="button" class="btn btn-info" id="fulfillment_audit_trails_button" data-id="<?= htmlspecialchars($id_rfq, ENT_QUOTES, 'UTF-8') ?>">Audit Trails</button>
        </div>
        <div class="col-sm-4 text-right">
          <?php include_once 'templates/pending_status.inc.php'; ?>
        </div>
      </div>
      <div class="row mb-2">
        <div class="col-md-12" id="invoice-dropdown"></div>
      </div>
    </div>
  </section>

  <!-- Main Content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <?php include_once 'plantillas/fulfillment/templates/fulfillment_table.inc.php'; ?>
        <div class="card-footer footer_item d-flex justify-content-between">
          <a class="btn btn-secondary" id="go_back" href="<?= EDITAR_COTIZACION . '/' . htmlspecialchars($quote->obtener_id(), ENT_QUOTES, 'UTF-8') ?>">
            <i class="fa fa-reply"></i> Go Back
          </a>
          <a href="#" id="add_comment" class="btn btn-secondary">
            <i class="fas fa-plus"></i> Add Comment
          </a>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Include Modals -->
<?php
$modals = [
  'modals/edit_fulfillment_shipping_modal.inc.php',
  'plantillas/fulfillment/modals/edit_fulfillment_subitem_modal.inc.php',
  'plantillas/fulfillment/modals/edit_fulfillment_item_modal.inc.php',
  'plantillas/fulfillment/modals/add_fulfillment_item_modal.inc.php',
  'plantillas/fulfillment/modals/add_fulfillment_subitem_modal.inc.php',
  'plantillas/fulfillment/modals/add_fulfillment_service_modal.inc.php',
  'plantillas/fulfillment/modals/edit_fulfillment_service_modal.inc.php',
  'plantillas/fulfillment/modals/new_comment_modal.inc.php',
  'plantillas/fulfillment/modals/comments_modal.inc.php',
  'modals/audit_trails_modal.inc.php',
  'modals/add_invoice_modal.inc.php',
  'modals/edit_invoice_modal.inc.php',
];

foreach ($modals as $modal) {
  include_once $modal;
}
?>

<script src="<?= RUTA_JS; ?>fulfillment.js"></script>