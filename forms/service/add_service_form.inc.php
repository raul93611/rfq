<?php
Conexion::abrir_conexion();
$rooms = RoomRepository::getAll(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_id());
Conexion::cerrar_conexion();
?>
<form id="add_service_form" method="post" enctype="multipart/form-data" action="<?php echo ADD_SERVICE; ?>">
  <div class="form-group">
    <label for="description">Description:</label>
    <textarea name="description" rows="5" cols="80" class="form-control form-control-sm"></textarea>
  </div>
  <div class="form-group">
    <label for="quantity">Quantity:</label>
    <input type="number" step=".01" name="quantity" class="form-control form-control-sm" value="">
  </div>
  <div class="form-group">
    <label for="unit_price">Unit Price:</label>
    <input type="number" step=".01" name="unit_price" class="form-control form-control-sm" value="">
  </div>
  <?php if (count($rooms)) : ?>
    <div class="form-group">
      <label for="id_room">Room:</label>
      <select class="custom-select" name="id_room">
        <option selected value="">Open this select menu</option>
        <?php foreach ($rooms as $key => $room) : ?>
          <option value="<?= $room->getId(); ?>"><?= $room->getName() ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  <?php endif; ?>
  <input type="hidden" name="id_rfq" value="<?php echo $cotizacion_recuperada->obtener_id(); ?>">
</form>