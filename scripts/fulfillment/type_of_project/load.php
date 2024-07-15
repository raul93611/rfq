<?php
header('Content-Type: text/html; charset=UTF-8');

try {
  // Validate and sanitize input
  if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    throw new Exception('Invalid or missing ID.');
  }

  $id = (int)$_POST['id'];

  // Open the database connection
  Conexion::abrir_conexion();

  // Fetch the type of project
  $typeOfProject = TypeOfProjectRepository::getById(Conexion::obtener_conexion(), $id);

  // Close the database connection
  Conexion::cerrar_conexion();

  if (!$typeOfProject) {
    throw new Exception('Type of project not found.');
  }
} catch (Exception $e) {
  // Ensure the database connection is closed in case of an error
  Conexion::cerrar_conexion();

  // Display an error message to the user (this can be improved to show a user-friendly message)
  echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</div>';
  exit;
}
?>

<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" class="form-control form-control-sm" value="<?= htmlspecialchars($typeOfProject->getName(), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
    </div>
  </div>
</div>
<input type="hidden" name="id_type_of_project" value="<?= htmlspecialchars($typeOfProject->getId(), ENT_QUOTES, 'UTF-8'); ?>">
<div class="modal-footer">
  <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>