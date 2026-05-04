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

<div class="user-form">
  <div class="form-group">
    <label for="description">Description</label>
    <textarea name="description" rows="4" class="form-control form-control-sm" required><?= html_entity_decode($service->get_description(), ENT_QUOTES | ENT_HTML5, 'UTF-8') ?></textarea>
  </div>

  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="quantity">Quantity</label>
      <input type="number" step=".01" min="0" name="quantity" class="form-control form-control-sm" value="<?= htmlspecialchars($service->get_quantity(), ENT_QUOTES, 'UTF-8'); ?>" required>
    </div>
    <div class="form-group col-md-6">
      <label for="unit_price">Unit Price (USD)</label>
      <div class="input-group input-group">
        <div class="input-group-prepend"><span class="input-group-text">$</span></div>
        <input type="number" step=".01" min="0" name="unit_price" class="form-control" value="<?= htmlspecialchars($service->get_unit_price(), ENT_QUOTES, 'UTF-8'); ?>" required>
      </div>
    </div>
  </div>

  <?php if (!empty($rooms)) : ?>
    <div class="form-group mb-0">
      <label for="id_room">Room</label>
      <select class="form-control form-control-sm" name="id_room">
        <option value="">— Select a room —</option>
        <?php foreach ($rooms as $room) : ?>
          <option value="<?= htmlspecialchars($room->getId()); ?>" <?= $room->getId() == $service->getIdRoom() ? 'selected' : '' ?>>
            <?= htmlspecialchars($room->getName()); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  <?php endif; ?>
</div>

<input type="hidden" name="id_rfq" value="<?= htmlspecialchars($service->get_id_rfq(), ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="id_service" value="<?= htmlspecialchars($id_service, ENT_QUOTES, 'UTF-8'); ?>">