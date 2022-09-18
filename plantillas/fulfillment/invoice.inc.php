<?php
Conexion::abrir_conexion();
$invoice = InvoiceRepository::get_one(Conexion::obtener_conexion(), $id_invoice);
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $invoice-> get_id_rfq());
$previous_invoice = InvoiceRepository::get_previous_invoice(Conexion::obtener_conexion(), $id_invoice, $invoice-> get_id_rfq());
$previous_date = is_null($previous_invoice) ? $quote-> obtener_fulfillment_date() : $previous_invoice-> get_created_at();
$total_items = RepositorioRfq::get_fulfillment_total_from_to(Conexion::obtener_conexion(), $invoice-> get_id_rfq(), $previous_date, $invoice-> get_created_at());
$total_services = RepositorioRfq::get_services_fulfillment_total_from_to(Conexion::obtener_conexion(), $invoice-> get_id_rfq(), $previous_date, $invoice-> get_created_at());
Conexion::cerrar_conexion();
?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-10">
          <h1 class="m-0 text-dark">Invoice #<?php echo $invoice-> get_name(); ?></h1>
        </div>
        <div class="col-sm-2">
          <a href="<?php echo DELETE_INVOICE . $id_invoice; ?>" class="btn btn-danger"><i class="fas fa-trash"></i></a>
          <button id="edit_invoice" class="btn btn-primary"><i class="fas fa-pen"></i></button>
        </div>
      </div>
    </div>
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <section class="col-12">
          <?php include_once 'plantillas/fulfillment/templates/invoice_table.inc.php'; ?>
          <div class="card-footer footer_item">
            <a class="btn btn-primary" id="go_back" href="<?php echo FULFILLMENT . '/' . $invoice->get_id_rfq(); ?>"><i class="fa fa-reply"></i></a>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>
<?php
include_once 'modals/edit_invoice_modal.inc.php';
?>
<script src="<?php echo RUTA_JS; ?>fulfillment.js"></script>