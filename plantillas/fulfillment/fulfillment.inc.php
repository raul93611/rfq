<?php
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$providers_list = ProviderListRepository::get_all(Conexion::obtener_conexion());
$items_exists = RepositorioItem::items_exists(Conexion::obtener_conexion(), $id_rfq);
$total_services = ServiceRepository::get_total(Conexion::obtener_conexion(), $id_rfq);
$payment_terms = PaymentTermRepository::get_all(Conexion::obtener_conexion());
$invoices = InvoiceRepository::get_all_by_id_rfq(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-3">
          <h1>Fulfillment table</h1>
        </div>
        <div class="col-sm-5 text-center">
          <?php
          include_once 'plantillas/fulfillment/templates/comments_button.inc.php';
          include_once 'plantillas/fulfillment/templates/pending_button.inc.php';
          ?>
          <button type="button" class="btn btn-info" id="fulfillment_audit_trails_button" data="<?php echo $id_rfq; ?>" name="button">Audit Trails</button>
        </div>
        <div class="col-sm-4 text-right">
          <?php include_once 'templates/pending_status.inc.php'; ?>
        </div>
      </div>
      <div class="row mb-2">
        <div class="col-md-12">
          <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Invoices
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="#" id="add_invoice"><i class="fas fa-plus"></i> Add Invoice</a>
              <?php
              foreach ($invoices as $key => $invoice) {
              ?>
                <a class="dropdown-item" href="<?php echo INVOICE . $invoice-> get_id(); ?>"><?php echo $invoice-> get_name(); ?></a>
              <?php
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <?php
    if ($quote->obtener_invoice()) {
      include_once 'plantillas/fulfillment/templates/sales_commission.inc.php';
    }
    ?>
    <div class="container-fluid">
      <div class="row">
        <?php include_once 'plantillas/fulfillment/templates/fulfillment_table.inc.php'; ?>
        <div class="card-footer footer_item">
          <a class="btn btn-primary" id="go_back" href="<?php echo EDITAR_COTIZACION . '/' . $quote->obtener_id(); ?>"><i class="fa fa-reply"></i></a>
          <a href="#" id="add_comment" class="btn btn-primary add_item_charter"><i class="fas fa-plus"></i> Add comment</a>
        </div>
      </div>
    </div>
  </section>
</div>
<?php
include_once 'modals/edit_fulfillment_shipping_modal.inc.php';
include_once 'plantillas/fulfillment/modals/edit_fulfillment_subitem_modal.inc.php';
include_once 'plantillas/fulfillment/modals/edit_fulfillment_item_modal.inc.php';
include_once 'plantillas/fulfillment/modals/add_fulfillment_item_modal.inc.php';
include_once 'plantillas/fulfillment/modals/add_fulfillment_subitem_modal.inc.php';
include_once 'plantillas/fulfillment/modals/add_fulfillment_service_modal.inc.php';
include_once 'plantillas/fulfillment/modals/edit_fulfillment_service_modal.inc.php';
include_once 'plantillas/fulfillment/modals/new_comment_modal.inc.php';
include_once 'plantillas/fulfillment/modals/comments_modal.inc.php';
include_once 'modals/audit_trails_modal.inc.php';
include_once 'modals/add_invoice_modal.inc.php';
?>
<script src="<?php echo RUTA_JS; ?>fulfillment.js"></script>