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
  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">
        Fulfillment
        <?php include_once 'templates/pending_status.inc.php'; ?>
      </h1>
      <p class="page-subtitle"><?= htmlspecialchars($quote->obtener_contract_number(), ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <div class="d-flex align-items-center" style="gap:8px;">
      <div id="invoice-dropdown"></div>
      <?php include_once 'plantillas/fulfillment/templates/comments_button.inc.php'; ?>
      <?php include_once 'plantillas/fulfillment/templates/pending_button.inc.php'; ?>
      <button type="button" class="btn btn-secondary btn-sm" id="fulfillment_audit_trails_button" data-id="<?= htmlspecialchars($id_rfq, ENT_QUOTES, 'UTF-8') ?>">
        <i class="fas fa-history mr-1"></i> Audit Trails
      </button>
    </div>
  </div>

  <!-- Main Content -->
  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="chart-card card-body">
        <?php include_once 'plantillas/fulfillment/templates/fulfillment_table.inc.php'; ?>
        <div class="quote-action-bar">
          <div class="quote-action-bar__left">
            <a class="btn btn-secondary btn-sm" id="go_back" href="<?= EDITAR_COTIZACION . '/' . htmlspecialchars($quote->obtener_id(), ENT_QUOTES, 'UTF-8') ?>">
              <i class="fa fa-reply mr-1"></i> Back
            </a>
            <a href="#" id="add_comment" class="btn btn-secondary btn-sm">
              <i class="fas fa-plus mr-1"></i> Add Comment
            </a>
          </div>
          <div class="quote-action-bar__totals">
            <div class="quote-action-total">
              <span class="quote-action-total__label">Total Price</span>
              <span class="quote-action-total__value">$<?= number_format($quote->obtener_quote_total_price(), 2); ?></span>
            </div>
            <div class="quote-action-total">
              <span class="quote-action-total__label">Total Profit</span>
              <span class="quote-action-total__value">$<?= number_format($quote->obtener_real_fulfillment_profit(), 2); ?></span>
            </div>
            <div class="quote-action-total">
              <span class="quote-action-total__label">Profit %</span>
              <span class="quote-action-total__value"><?= number_format($quote->obtener_real_fulfillment_profit_percentage(), 2); ?>%</span>
            </div>
          </div>
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