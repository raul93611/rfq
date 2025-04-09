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
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }
  echo '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</div>';
  exit;
} finally {
  // Ensure the connection is closed
  Conexion::cerrar_conexion();
}
?>

<div class="modal-body">
  <div class="form-group">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" class="form-control form-control-sm" value="<?= htmlspecialchars($personnel->getName(), ENT_QUOTES, 'UTF-8'); ?>" required>
    <small class="form-text text-muted">Enter the full name of the personnel.</small>
  </div>
  <div class="form-group">
    <label for="criteria">Criteria:</label>
    <select name="criteria" class="custom-select" id="criteria" required>
      <option value="CONTRACTOR" <?= $personnel->getCriteria() == 'CONTRACTOR' ? 'selected' : '' ?>>CONTRACTOR</option>
      <option value="PAYROLL" <?= $personnel->getCriteria() == 'PAYROLL' ? 'selected' : '' ?>>PAYROLL</option>
    </select>
    <small class="form-text text-muted">Select the employment criteria for this personnel.</small>
  </div>
  <div class="form-group">
    <label for="id_type_of_project">Type:</label>
    <select name="id_type_of_project" class="custom-select" id="id_type_of_project" required>
      <option value="" disabled selected>Select Project Type</option>
      <?php foreach ($types_of_projects as $type_of_project) : ?>
        <option value="<?= $type_of_project->getId() ?>" <?= $type_of_project->getId() == $personnel->getIdTypeOfProject() ? 'selected' : '' ?>>
          <?= htmlspecialchars($type_of_project->getName(), ENT_QUOTES, 'UTF-8') ?>
        </option>
      <?php endforeach; ?>
    </select>
    <small class="form-text text-muted">Choose the type of project associated with this personnel.</small>
  </div>
  <input type="hidden" name="id_personnel" value="<?= htmlspecialchars($personnel->getId(), ENT_QUOTES, 'UTF-8'); ?>">
</div>

<div class="modal-footer">
  <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>