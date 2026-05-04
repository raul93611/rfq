<?php
Conexion::abrir_conexion();
try {
  $conexion = Conexion::obtener_conexion();
  $room = !isset($_POST["idRoom"]) ? null : RoomRepository::getById($conexion, $_POST["idRoom"]);
} finally {
  Conexion::cerrar_conexion();
}
?>
<div class="modal-body user-form" style="padding:20px;">
  <div class="form-group">
    <label for="name">Name</label>
    <input type="text" id="name" class="form-control form-control-sm" name="name" placeholder="e.g. Conference Room A" value="<?= $room ? htmlspecialchars($room->getName()) : ''; ?>" autofocus>
  </div>
  <div class="form-group mb-0">
    <label for="color">Color</label>
    <div class="input-group input-group-sm">
      <input type="text" id="color" class="form-control" name="color" placeholder="#337ab7" value="<?= $room ? htmlspecialchars($room->getColor()) : ''; ?>">
      <div class="input-group-append">
        <span class="input-group-text" style="color:<?= $room ? htmlspecialchars($room->getColor()) : '#337ab7'; ?>;">
          <i class="fas fa-square"></i>
        </span>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="id_rfq" name="id_rfq" value="<?= htmlspecialchars($_POST["idRfq"] ?? '', ENT_QUOTES, 'UTF-8') ?>">
<input type="hidden" id="id_room" name="id_room" value="<?= htmlspecialchars($room?->getId() ?? '', ENT_QUOTES, 'UTF-8'); ?>">
<div class="modal-footer" style="border-top:1px solid #f0f2f5;padding:12px 20px;justify-content:<?= isset($_POST["idRoom"]) ? 'space-between' : 'flex-end'; ?>;gap:8px;">
  <?php if (isset($_POST["idRoom"])) : ?>
    <button type="button" id="delete-room-button" data-id="<?= htmlspecialchars($_POST["idRoom"]) ?>" class="btn btn-danger btn-sm">
      <i class="fa fa-trash mr-1"></i> Delete
    </button>
  <?php endif; ?>
  <div style="display:flex;gap:8px;">
    <button type="submit" class="btn btn-primary btn-sm">
      <i class="fa fa-check mr-1"></i> <?= isset($_POST["idRoom"]) ? 'Save Changes' : 'Add Room'; ?>
    </button>
    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
      <i class="fa fa-times mr-1"></i> Cancel
    </button>
  </div>
</div>