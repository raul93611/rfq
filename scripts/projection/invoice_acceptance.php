<?php
// Open database connection
Conexion::abrir_conexion();

try {
  // Validate and sanitize input
  if (isset($_POST['id']) && is_numeric($_POST['id']) && isset($_POST['partialInvoice'])) {
    $id = intval($_POST['id']);
    $partialInvoice = filter_var($_POST['partialInvoice'], FILTER_VALIDATE_BOOLEAN);

    // Get invoice acceptance based on partialInvoice flag
    $invoiceAcceptance = $partialInvoice
      ? InvoiceRepository::getInvoiceAcceptance(Conexion::obtener_conexion(), $id)
      : RepositorioRfq::getInvoiceAcceptance(Conexion::obtener_conexion(), $id);
  } else {
    throw new Exception("Invalid input.");
  }
} catch (Exception $e) {
  // Handle errors gracefully
  echo "<small class='text-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</small>";
  $invoiceAcceptance = '';
} finally {
  // Close database connection
  Conexion::cerrar_conexion();
}
?>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="invoice_acceptance">Invoice Acceptance:</label>
        <textarea name="invoice_acceptance" id="invoice_acceptance" rows="5" class="form-control form-control-sm"><?= htmlspecialchars($invoiceAcceptance ?? '') ?></textarea>
        <small class="form-text text-muted">Provide detailed invoice acceptance notes if applicable.</small>
      </div>
    </div>
  </div>
</div>
<input type="hidden" name="id" value="<?= htmlspecialchars($id ?? '') ?>">
<input type="hidden" name="partial_invoice" value="<?= htmlspecialchars($partialInvoice ?? '') ?>">
<div class="modal-footer">
  <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>