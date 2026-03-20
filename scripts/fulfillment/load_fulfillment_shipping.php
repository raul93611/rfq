<?php
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
Conexion::cerrar_conexion();

$shippings = explode('|', $quote->obtener_fulfillment_shipping() ?? '');
$costs = explode('|', $quote->obtener_fulfillment_shipping_cost() ?? '');
?>

<div class="modal-body user-form">

  <div class="shipping_container">
    <?php foreach ($shippings as $key => $shipping) : ?>
      <div class="shipping<?= htmlspecialchars($key); ?> mb-3">
        <div class="form-row">
          <div class="col-md-8">
            <div class="form-group mb-0">
              <label>Description</label>
              <input type="hidden" name="fulfillment_shipping_original<?= htmlspecialchars($key); ?>" value="<?= htmlspecialchars($shipping); ?>">
              <input type="text" class="form-control form-control-sm" id="fulfillment_shipping<?= htmlspecialchars($key); ?>"
                name="fulfillment_shipping<?= htmlspecialchars($key); ?>" value="<?= htmlspecialchars($shipping); ?>">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group mb-0">
              <label>Amount</label>
              <input type="hidden" name="amount_original<?= htmlspecialchars($key); ?>" value="<?= htmlspecialchars($costs[$key] ?? 0); ?>">
              <div class="input-group input-group">
                <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                <input type="number" step=".01" id="amount<?= htmlspecialchars($key); ?>" class="form-control"
                  name="amount<?= htmlspecialchars($key); ?>" value="<?= htmlspecialchars($costs[$key] ?? 0); ?>">
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <input type="hidden" name="shipping_counter" value="<?= count($shippings) - 1; ?>">

  <div class="d-flex" style="gap:8px; margin-top:12px;">
    <button type="button" class="add_shipping btn btn-secondary btn-sm"><i class="fas fa-plus mr-1"></i> Add</button>
    <button type="button" class="remove_shipping btn btn-secondary btn-sm"><i class="fas fa-minus mr-1"></i> Remove</button>
  </div>

</div>

<div class="modal-footer d-flex justify-content-end" style="gap:8px;">
  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-ban mr-1"></i> Cancel</button>
  <button type="submit" name="update_fulfillment_shipping_button" class="btn btn-primary btn-sm"><i class="fa fa-check mr-1"></i> Save</button>
</div>
