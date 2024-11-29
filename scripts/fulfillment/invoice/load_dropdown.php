<?php
try {
  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch the quote and invoices
  $quote = RepositorioRfq::obtener_cotizacion_por_id($conexion, $_POST["id"]);
  $invoices = InvoiceRepository::get_all_by_id_rfq($conexion, $_POST["id"]);

  // Check if the quote was successfully fetched
  if (!$quote) {
    throw new Exception('Quote not found.');
  }

  // Close the database connection
  Conexion::cerrar_conexion();
} catch (Exception $e) {
  // Handle any exceptions
  Conexion::cerrar_conexion();
  echo '<div class="alert alert-danger">An error occurred: ' . htmlspecialchars($e->getMessage()) . '</div>';
  exit;
}
?>

<?php if ($quote->obtener_fulfillment_pending()) : ?>
  <div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Invoices
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <a class="dropdown-item" href="#" id="add_invoice"><i class="fas fa-plus"></i> Add Invoice</a>
      <?php foreach ($invoices as $invoice) : ?>
        <a class="edit-invoice-button dropdown-item" data-id="<?= htmlspecialchars($invoice->get_id()) ?>" href="#"><?= htmlspecialchars($invoice->get_name()); ?></a>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>