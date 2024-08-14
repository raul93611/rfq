<?php
Conexion::abrir_conexion();
$rooms = RoomRepository::getAll(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
?>

<div class="btn-group dropup">
  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fas fa-hotel"></i> Rooms
  </button>
  <div class="dropdown-menu">
    <button class="dropdown-item" id="add-room-button"><i class="fa fa-plus"></i> Add</button>
    <?php if (count($rooms)) : ?>
      <div class="dropdown-divider"></div>
      <?php foreach ($rooms as $key => $room) : ?>
        <button data-id="<?= $room->getId(); ?>" class="edit-room-button dropdown-item"><?= $room->getName() ?></button>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>