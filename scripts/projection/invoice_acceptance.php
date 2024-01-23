<?php
Conexion::abrir_conexion();
if ($_POST["partialInvoice"]) {
  $invoiceAcceptance = InvoiceRepository::getInvoiceAcceptance(Conexion::obtener_conexion(), $_POST['id']);
} else {
  $invoiceAcceptance = RepositorioRfq::getInvoiceAcceptance(Conexion::obtener_conexion(), $_POST['id']);
}
Conexion::cerrar_conexion();
?>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="invoice_acceptance">Invoice Acceptance:</label>
        <textarea name="invoice_acceptance" id="invoice_acceptance" rows="5" class="form-control form-control-sm"><?= $invoiceAcceptance ?></textarea>
      </div>
    </div>
  </div>
</div>
<input type="hidden" name="id" value="<?= $_POST["id"]; ?>">
<input type="hidden" name="partial_invoice" value="<?= $_POST["partialInvoice"]; ?>">
<div class="modal-footer">
  <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>