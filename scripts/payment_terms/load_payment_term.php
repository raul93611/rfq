<?php
try {
  // Validate and sanitize the input
  if (isset($id_payment_term) && is_numeric($id_payment_term)) {
    $id_payment_term = intval($id_payment_term);
  } else {
    throw new Exception("Invalid payment term ID");
  }

  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch the payment term
  $payment_term = PaymentTermRepository::get_one($conexion, $id_payment_term);

  // Close the database connection
  Conexion::cerrar_conexion();

  if (!$payment_term) {
    throw new Exception("Payment term not found");
  }
} catch (Exception $e) {
  // Handle exceptions and display error message
  echo "Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
  exit();
}
?>

<input type="hidden" name="id_payment_term" value="<?= htmlspecialchars($id_payment_term, ENT_QUOTES, 'UTF-8'); ?>">
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="name">Payment Term:</label>
        <input type="text" id="name" class="form-control form-control-sm" name="name" value="<?= $payment_term->get_payment_term(); ?>" required>
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