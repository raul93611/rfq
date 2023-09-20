<?php
Conexion::abrir_conexion();
$event = CalendarEventRepository::getById(Conexion::obtener_conexion(), $_POST['id']);
Conexion::cerrar_conexion();
?>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" class="form-control form-control-sm" value="<?= $event->getName(); ?>">
      </div>
      <div class="form-group">
        <label for="start">Start:</label>
        <input readonly type="text" id="start" class="form-control form-control-sm" name="start" value="<?= date("m/d/Y", strtotime($event->getStart())) ?>">
      </div>
      <div class="form-group">
        <label for="end">End:</label>
        <input readonly type="text" id="end" class="form-control form-control-sm" name="end" value="<?= date("m/d/Y", strtotime($event->getEnd())) ?>">
      </div>
      <div class="form-group">
        <label for="color">Color:</label>
        <div class="input-group">
          <input type="text" id="color" class="form-control form-control-sm" name="color" value="<?= $event->getColor() ?>">
          <div class="input-group-append">
            <span class="input-group-text" style="color: <?= $event->getColor() ?>;"><i class="fas fa-square"></i></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<input type="hidden" name="id_event" value="<?= $event->getId(); ?>">
<div class="modal-footer">
  <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" data-id="<?= $event->getId(); ?>" class="delete-event-button btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
  <button type="button" class="btn btn-info" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>