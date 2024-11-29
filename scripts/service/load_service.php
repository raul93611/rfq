<?php
try {
  Conexion::abrir_conexion();
  $service = ServiceRepository::get_service(Conexion::obtener_conexion(), $id_service);
  $rooms = RoomRepository::getAll(Conexion::obtener_conexion(), $service->get_id_rfq());
} catch (Exception $e) {
  // Print the error directly
  die('Error retrieving the service details: ' . htmlspecialchars($e->getMessage()));
} finally {
  Conexion::cerrar_conexion();
}
?>

<div class="form-group">
  <label for="description">Description:</label>
  <textarea name="description" rows="5" class="form-control form-control-sm" required><?= html_entity_decode($service->get_description(), ENT_QUOTES | ENT_HTML5, 'UTF-8') ?></textarea>
  <small class="form-text text-muted">Provide a brief description of the service.</small>
</div>

<div class="form-group">
  <label for="quantity">Quantity:</label>
  <input type="number" step=".01" name="quantity" class="form-control form-control-sm" value="<?= htmlspecialchars($service->get_quantity(), ENT_QUOTES, 'UTF-8'); ?>" required>
  <small class="form-text text-muted">Specify the quantity of the service.</small>
</div>

<div class="form-group">
  <label for="unit_price">Unit Price:</label>
  <input type="number" step=".01" name="unit_price" class="form-control form-control-sm" value="<?= htmlspecialchars($service->get_unit_price(), ENT_QUOTES, 'UTF-8'); ?>" required>
  <small class="form-text text-muted">Enter the price per unit of the service.</small>
</div>

<?php if (!empty($rooms)) : ?>
  <div class="form-group">
    <label for="id_room">Room:</label>
    <select class="custom-select" name="id_room">
      <option value="">Select a room</option>
      <?php foreach ($rooms as $room) : ?>
        <option value="<?= htmlspecialchars($room->getId()); ?>" <?= $room->getId() == $service->getIdRoom() ? 'selected' : '' ?>>
          <?= htmlspecialchars($room->getName()); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <small class="form-text text-muted">Select the room where the service will be applied.</small>
  </div>
<?php endif; ?>

<input type="hidden" name="id_rfq" value="<?= htmlspecialchars($service->get_id_rfq(), ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="id_service" value="<?= htmlspecialchars($id_service, ENT_QUOTES, 'UTF-8'); ?>">