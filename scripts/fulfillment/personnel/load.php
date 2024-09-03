<?php
try {
  // Open the database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion(); // Store the connection to reuse

  // Validate and sanitize the input
  if (empty($_POST['id']) || !is_numeric($_POST['id'])) {
    throw new Exception('Invalid personnel ID.');
  }
  $id = (int)$_POST['id'];

  // Fetch personnel and types of projects data
  $personnel = PersonnelRepository::getById($conexion, $id);
  $types_of_projects = TypeOfProjectRepository::getAll($conexion);

  if (!$personnel) {
    throw new Exception('Personnel not found.');
  }
} catch (Exception $e) {
  // Handle exceptions and close the connection if open
  if ($conexion) {
    Conexion::cerrar_conexion();
  }
  echo '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>';
  exit;
} finally {
  // Ensure the connection is closed
  Conexion::cerrar_conexion();
}
?>

<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" class="form-control form-control-sm" value="<?= htmlspecialchars($personnel->getName()); ?>">
      </div>
      <div class="form-group">
        <label for="criteria">Criteria:</label>
        <select name="criteria" class="custom-select" id="criteria">
          <option value="CONTRACTOR" <?= $personnel->getCriteria() == 'CONTRACTOR' ? 'selected' : '' ?>>CONTRACTOR</option>
          <option value="PAYROLL" <?= $personnel->getCriteria() == 'PAYROLL' ? 'selected' : '' ?>>PAYROLL</option>
        </select>
      </div>
      <div class="form-group">
        <label for="id_type_of_project">Type:</label>
        <select name="id_type_of_project" class="custom-select" id="id_type_of_project">
          <?php foreach ($types_of_projects as $type_of_project) : ?>
            <option value="<?= $type_of_project->getId() ?>" <?= $type_of_project->getId() == $personnel->getIdTypeOfProject() ? 'selected' : '' ?>>
              <?= htmlspecialchars($type_of_project->getName()) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>
</div>

<input type="hidden" name="id_personnel" value="<?= $personnel->getId(); ?>">

<div class="modal-footer">
  <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>