<?php
try {
  // Validate and sanitize input
  if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    throw new Exception('Invalid input parameters.');
  }

  $id = (int) $_POST['id'];

  // Open the database connection
  Conexion::abrir_conexion();

  // Fetch the event details
  $event = CalendarEventRepository::getById(Conexion::obtener_conexion(), $id);

  // Close the database connection
  Conexion::cerrar_conexion();

  if (!$event) {
    throw new Exception('Event not found.');
  }
} catch (Exception $e) {
  // Ensure the database connection is closed in case of an error
  Conexion::cerrar_conexion();

  // Render an error message (modify as per your application's error handling strategy)
  echo '<div class="alert alert-danger">An error occurred: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</div>';
  exit;
}
?>

<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" class="form-control form-control-sm" value="<?= htmlspecialchars($event->getName(), ENT_QUOTES, 'UTF-8'); ?>" required>
        <small class="form-text text-muted">Enter the name of the event. This is required.</small>
      </div>
      <div class="form-group">
        <label for="start">Start:</label>
        <input readonly type="text" id="start" class="form-control form-control-sm" name="start" value="<?= htmlspecialchars(date("m/d/Y", strtotime($event->getStart())), ENT_QUOTES, 'UTF-8'); ?>">
        <small class="form-text text-muted">The date and time the event starts.</small>
      </div>
      <div class="form-group">
        <label for="end">End:</label>
        <input readonly type="text" id="end" class="form-control form-control-sm" name="end" value="<?= htmlspecialchars(date("m/d/Y", strtotime($event->getEnd())), ENT_QUOTES, 'UTF-8'); ?>">
        <small class="form-text text-muted">The date and time the event ends.</small>
      </div>
      <div class="form-group">
        <label for="color">Color:</label>
        <div class="input-group">
          <input type="text" id="color" class="form-control form-control-sm" name="color" value="<?= htmlspecialchars($event->getColor(), ENT_QUOTES, 'UTF-8'); ?>" required>
          <div class="input-group-append">
            <span class="input-group-text" style="color: <?= htmlspecialchars($event->getColor(), ENT_QUOTES, 'UTF-8'); ?>;">
              <i class="fas fa-square"></i>
            </span>
          </div>
        </div>
        <small class="form-text text-muted">Choose a color to represent the event in the calendar. This is required.</small>
      </div>
    </div>
  </div>
</div>
<input type="hidden" name="id_event" value="<?= htmlspecialchars($event->getId(), ENT_QUOTES, 'UTF-8'); ?>">
<div class="modal-footer">
  <button type="submit" class="btn btn-success">
    <i class="fa fa-check"></i> Save
  </button>
  <button type="button" data-id="<?= htmlspecialchars($event->getId(), ENT_QUOTES, 'UTF-8'); ?>" class="delete-event-button btn btn-danger">
    <i class="fa fa-trash"></i> Delete
  </button>
  <button type="button" class="btn btn-info" data-dismiss="modal">
    <i class="fa fa-ban"></i> Cancel
  </button>
</div>