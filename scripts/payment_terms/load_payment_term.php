<?php
Conexion::abrir_conexion();
$payment_term = PaymentTermRepository::get_one(Conexion::obtener_conexion(), $id_payment_term);
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_payment_term" value="<?php echo $id_payment_term;?>">
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="name">Payment Term:</label>
        <input type="text" id="name" class="form-control form-control-sm" name="name" value="<?php echo $payment_term-> get_payment_term(); ?>" required>
        <div class="error_message">
          Name cannot be empty and has to be different from existing ones.
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="submit" name="save" form="edit_payment_term_form" class="btn btn-success">Save</button>
</div>
