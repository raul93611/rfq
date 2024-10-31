<?php
Conexion::abrir_conexion();
$types_of_projects = TypeOfProjectRepository::getAll(Conexion::obtener_conexion());
Conexion::cerrar_conexion();
?>

<div class="modal fade" id="add-personnel-modal" tabindex="-1" role="dialog" aria-labelledby="addPersonnelModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addPersonnelModalLabel">Add Personnel</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-personnel-form" method="post" enctype="multipart/form-data" action="">
          <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" class="form-control form-control-sm" name="name" value="" required>
            <small class="form-text text-muted">Enter the full name of the personnel.</small>
          </div>
          <div class="form-group">
            <label for="criteria">Criteria:</label>
            <select name="criteria" class="custom-select" id="criteria" required>
              <option value="" disabled selected>Select Criteria</option>
              <option value="CONTRACTOR">CONTRACTOR</option>
              <option value="PAYROLL">PAYROLL</option>
            </select>
            <small class="form-text text-muted">Choose the employment criteria.</small>
          </div>
          <div class="form-group">
            <label for="id_type_of_project">Type:</label>
            <select name="id_type_of_project" class="custom-select" id="id_type_of_project" required>
              <option value="" disabled selected>Select Type of Project</option>
              <?php foreach ($types_of_projects as $type_of_project) : ?>
                <option value="<?= $type_of_project->getId() ?>"><?= htmlspecialchars($type_of_project->getName(), ENT_QUOTES, 'UTF-8') ?></option>
              <?php endforeach; ?>
            </select>
            <small class="form-text text-muted">Select the type of project for the personnel.</small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="add-personnel-form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>