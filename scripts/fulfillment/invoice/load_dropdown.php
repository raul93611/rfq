<?php
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST["id"]);
$invoices = InvoiceRepository::get_all_by_id_rfq(Conexion::obtener_conexion(), $_POST["id"]);
Conexion::cerrar_conexion();
?>
<?php if ($quote->obtener_fulfillment_pending()) : ?>
  <div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Invoices
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <a class="dropdown-item" href="#" id="add_invoice"><i class="fas fa-plus"></i> Add Invoice</a>
      <?php foreach ($invoices as $key => $invoice) : ?>
        <a class="edit-invoice-button dropdown-item" data-id="<?= $invoice->get_id() ?>" href="#"><?= $invoice->get_name(); ?></a>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>