<?php
// Open the database connection
Conexion::abrir_conexion();

// Retrieve the invoice data based on the provided ID
$invoice = InvoiceRepository::get_one(Conexion::obtener_conexion(), $_POST['id']);

// Close the database connection
Conexion::cerrar_conexion();
?>

<!-- Hidden input to store the invoice ID -->
<input type="hidden" name="id_invoice" value="<?= htmlspecialchars($invoice->get_id(), ENT_QUOTES, 'UTF-8'); ?>">

<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" class="form-control form-control-sm" name="name" value="<?= htmlspecialchars($invoice->get_name(), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="form-group">
        <label for="created_at">Created At:</label>
        <input type="text" id="created_at" readonly class="form-control form-control-sm" name="created_at" value="<?= date("m/d/Y", strtotime($invoice->get_created_at())); ?>">
      </div>
    </div>
  </div>
  <!-- Error message container -->
  <div id="error"></div>
</div>

<div class="modal-footer">
  <button type="submit" class="btn btn-success">
    <i class="fa fa-check"></i> Save
  </button>
  <button type="button" data-id="<?= htmlspecialchars($invoice->get_id(), ENT_QUOTES, 'UTF-8'); ?>" class="delete-invoice-button btn btn-danger">
    <i class="fa fa-trash"></i> Delete
  </button>
  <button type="button" class="btn btn-danger" data-dismiss="modal">
    <i class="fa fa-ban"></i> Cancel
  </button>
</div>