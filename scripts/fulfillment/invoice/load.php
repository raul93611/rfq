<?php
Conexion::abrir_conexion();
$invoice = InvoiceRepository::get_one(Conexion::obtener_conexion(), $_POST['id']);
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_invoice" value="<?= $invoice->get_id() ?>">
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" class="form-control form-control-sm" name="name" value="<?= $invoice->get_name() ?>">
      </div>
      <div class="form-group">
        <label for="created_at">Created At:</label>
        <input type="text" id="created_at" readonly class="form-control form-control-sm" name="created_at" value="<?= date("m/d/Y", strtotime($invoice->get_created_at())) ?>">
      </div>
    </div>
  </div>
  <div id="error"></div>
</div>
<div class="modal-footer">
  <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" data-id="<?= $invoice->get_id(); ?>" class="delete-invoice-button btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>