<?php
Conexion::abrir_conexion();
try {
  $conexion = Conexion::obtener_conexion();
  $room = !isset($_POST["idRoom"]) ? null : RoomRepository::getById($conexion, $_POST["idRoom"]);
} finally {
  Conexion::cerrar_conexion();
}
?>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" class="form-control form-control-sm" name="name" value="<?= $room ? $room->getName() : ''; ?>">
        <small class="form-text text-muted">Enter the room name, e.g., "Conference Room A".</small>
      </div>
      <div class="form-group">
        <label for="color">Color:</label>
        <div class="input-group">
          <input type="text" id="color" class="form-control form-control-sm" name="color" value="<?= $room ? htmlspecialchars($room->getColor(), ENT_QUOTES, 'UTF-8') : ''; ?>">
          <div class="input-group-append">
            <span class="input-group-text" style="color: <?= $room ? htmlspecialchars($room->getColor(), ENT_QUOTES, 'UTF-8') : ''; ?>;"><i class="fas fa-square"></i></span>
          </div>
        </div>
        <small class="form-text text-muted">Enter a valid hex color code, e.g., "#FF5733".</small>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="id_rfq" name="id_rfq" value="<?= $_POST["idRfq"] ?? '' ?>">
<input type="hidden" id="id_room" name="id_room" value="<?= htmlspecialchars($room?->getId() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
<div class="modal-footer">
  <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <?php if (isset($_POST["idRoom"])) : ?>
    <button type="submit" id="delete-room-button" data-id="<?= $_POST["idRoom"] ?>" class="btn btn-danger"><i class="fa fa-times"></i> Delete</button>
  <?php endif; ?>
  <button type="button" class="btn btn-info" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>