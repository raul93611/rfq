<?php
// Open database connection and retrieve rooms
Conexion::abrir_conexion();
$rooms = RoomRepository::getAll(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_id());
Conexion::cerrar_conexion();
?>

<form id="add_service_form" class="user-form" method="post" enctype="multipart/form-data" action="<?php echo ADD_SERVICE; ?>">

  <div class="form-group">
    <label for="description">Description</label>
    <textarea name="description" rows="4" class="form-control form-control-sm" placeholder="Enter service description" autofocus></textarea>
  </div>

  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="quantity">Quantity</label>
      <input type="number" step="0.01" min="0" name="quantity" class="form-control form-control-sm" placeholder="0">
    </div>
    <div class="form-group col-md-6">
      <label for="unit_price">Unit Price (USD)</label>
      <div class="input-group input-group">
        <div class="input-group-prepend"><span class="input-group-text">$</span></div>
        <input type="number" step="0.01" min="0" name="unit_price" class="form-control" placeholder="0.00">
      </div>
    </div>
  </div>

  <?php if (!empty($rooms)) : ?>
    <div class="form-group mb-0">
      <label for="id_room">Room</label>
      <select class="form-control form-control-sm" name="id_room">
        <option selected value="">— Select a room —</option>
        <?php foreach ($rooms as $room) : ?>
          <option value="<?= htmlspecialchars($room->getId()); ?>">
            <?= htmlspecialchars($room->getName()); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  <?php endif; ?>

  <input type="hidden" name="id_rfq" value="<?= htmlspecialchars($cotizacion_recuperada->obtener_id()); ?>">

</form>