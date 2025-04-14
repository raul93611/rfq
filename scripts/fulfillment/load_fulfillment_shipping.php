<?php
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
Conexion::cerrar_conexion();

$shippings = explode('|', $quote->obtener_fulfillment_shipping() ?? '');
$costs = explode('|', $quote->obtener_fulfillment_shipping_cost() ?? '');
?>

<div class="modal-body">
  <div class="row">
    <div class="shipping_container col-md-12">
      <?php foreach ($shippings as $key => $shipping) : ?>
        <div class="shipping<?= htmlspecialchars($key); ?>">
          <div class="form-group">
            <label for="fulfillment_shipping<?= htmlspecialchars($key); ?>">Description:</label>
            <input type="hidden" name="fulfillment_shipping_original<?= htmlspecialchars($key); ?>" value="<?= htmlspecialchars($shipping); ?>">
            <input type="text" class="form-control form-control-sm" id="fulfillment_shipping<?= htmlspecialchars($key); ?>"
              name="fulfillment_shipping<?= htmlspecialchars($key); ?>" value="<?= htmlspecialchars($shipping); ?>">
            <small class="form-text text-muted">Enter a description for the shipping option.</small>
          </div>

          <div class="form-group">
            <label for="amount<?= htmlspecialchars($key); ?>">Amount:</label>
            <input type="hidden" name="amount_original<?= htmlspecialchars($key); ?>" value="<?= htmlspecialchars($costs[$key] ?? 0); ?>">
            <input type="number" step=".01" id="amount<?= htmlspecialchars($key); ?>" class="form-control form-control-sm"
              name="amount<?= htmlspecialchars($key); ?>" value="<?= htmlspecialchars($costs[$key] ?? 0); ?>">
            <small class="form-text text-muted">Specify the cost associated with this shipping option.</small>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <input type="hidden" name="shipping_counter" value="<?= count($shippings) - 1; ?>">
  <button type="button" class="add_shipping btn btn-secondary"><i class="fas fa-plus"></i> Add</button>
  <button type="button" class="remove_shipping btn btn-secondary"><i class="fas fa-minus"></i> Remove</button>
</div>

<div class="modal-footer">
  <button type="submit" name="update_fulfillment_shipping_button" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>