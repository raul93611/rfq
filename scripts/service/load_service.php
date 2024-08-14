<?php
try {
  Conexion::abrir_conexion();
  $service = ServiceRepository::get_service(Conexion::obtener_conexion(), $id_service);
  $rooms = RoomRepository::getAll(Conexion::obtener_conexion(), $service->get_id_rfq());
} catch (Exception $e) {
  // Handle exceptions, e.g., log the error and display a user-friendly message
  error_log('ERROR: ' . $e->getMessage());
  // You might want to redirect or show an error message to the user here
  die('Error retrieving the service details.');
} finally {
  Conexion::cerrar_conexion();
}
?>
<div class="form-group">
  <label for="description">Description:</label>
  <textarea name="description" rows="5" cols="80" class="form-control form-control-sm" required><?= html_entity_decode($service->get_description(), ENT_QUOTES | ENT_HTML5, 'UTF-8') ?></textarea>
</div>
<div class="form-group">
  <label for="quantity">Quantity:</label>
  <input type="number" step=".01" name="quantity" class="form-control form-control-sm" value="<?= htmlspecialchars($service->get_quantity(), ENT_QUOTES, 'UTF-8'); ?>" required>
</div>
<div class="form-group">
  <label for="unit_price">Unit Price:</label>
  <input type="number" step=".01" name="unit_price" class="form-control form-control-sm" value="<?= htmlspecialchars($service->get_unit_price(), ENT_QUOTES, 'UTF-8'); ?>" required>
</div>
<?php if (count($rooms)) : ?>
  <div class="form-group">
    <label for="id_room">Room:</label>
    <select class="custom-select" name="id_room">
      <option value="">Open this select menu</option>
      <?php foreach ($rooms as $key => $room) : ?>
        <option value="<?= $room->getId(); ?>" <?= $room->getId() == $service->getIdRoom() ? 'selected' : '' ?>><?= $room->getName() ?></option>
      <?php endforeach; ?>
    </select>
  </div>
<?php endif; ?>
<input type="hidden" name="id_rfq" value="<?= htmlspecialchars($service->get_id_rfq(), ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="id_service" value="<?= htmlspecialchars($id_service, ENT_QUOTES, 'UTF-8'); ?>">