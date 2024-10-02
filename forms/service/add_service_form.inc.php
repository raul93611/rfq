<?php
// Open database connection and retrieve rooms
Conexion::abrir_conexion();
$rooms = RoomRepository::getAll(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_id());
Conexion::cerrar_conexion();
?>

<form id="add_service_form" method="post" enctype="multipart/form-data" action="<?php echo ADD_SERVICE; ?>">

  <!-- Description Field -->
  <div class="form-group">
    <label for="description">Description:</label>
    <textarea name="description" rows="5" class="form-control form-control-sm" placeholder="Enter service description"></textarea>
    <small class="form-text text-muted">Provide a brief description of the service.</small>
  </div>

  <!-- Quantity Field -->
  <div class="form-group">
    <label for="quantity">Quantity:</label>
    <input type="number" step="0.01" name="quantity" class="form-control form-control-sm" placeholder="Enter quantity" value="">
    <small class="form-text text-muted">Specify the quantity of the service.</small>
  </div>

  <!-- Unit Price Field -->
  <div class="form-group">
    <label for="unit_price">Unit Price:</label>
    <input type="number" step="0.01" name="unit_price" class="form-control form-control-sm" placeholder="Enter unit price" value="">
    <small class="form-text text-muted">Enter the price per unit of the service.</small>
  </div>

  <!-- Room Selection Field (Only if there are rooms) -->
  <?php if (!empty($rooms)) : ?>
    <div class="form-group">
      <label for="id_room">Room:</label>
      <select class="custom-select" name="id_room">
        <option selected value="">Select a room</option>
        <?php foreach ($rooms as $room) : ?>
          <option value="<?= htmlspecialchars($room->getId()); ?>">
            <?= htmlspecialchars($room->getName()); ?>
          </option>
        <?php endforeach; ?>
      </select>
      <small class="form-text text-muted">Select the room where the service will be applied.</small>
    </div>
  <?php endif; ?>

  <!-- Hidden Field for RFQ ID -->
  <input type="hidden" name="id_rfq" value="<?= htmlspecialchars($cotizacion_recuperada->obtener_id()); ?>">

</form>