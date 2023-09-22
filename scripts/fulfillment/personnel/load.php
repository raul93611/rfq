<?php
Conexion::abrir_conexion();
$personnel = PersonnelRepository::getById(Conexion::obtener_conexion(), $_POST['id']);
$types_of_projects = TypeOfProjectRepository::getAll(Conexion::obtener_conexion());
Conexion::cerrar_conexion();
?>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" class="form-control form-control-sm" value="<?= $personnel->getName(); ?>">
      </div>
      <div class="form-group">
        <label for="criteria">Criteria:</label>
        <select name="criteria" class="custom-select" id="criteria">
          <option <?= $personnel->getCriteria() == 'CONTRACTOR' ? 'selected' : '' ?>>CONTRACTOR</option>
          <option <?= $personnel->getCriteria() == 'PAYROLL' ? 'selected' : '' ?>>PAYROLL</option>
        </select>
      </div>
      <div class="form-group">
        <label for="id_type_of_project">Type:</label>
        <select name="id_type_of_project" class="custom-select" id="id_type_of_project">
          <?php foreach ($types_of_projects as $key => $type_of_project) : ?>
            <option value="<?= $type_of_project->getId() ?>" <?= $type_of_project->getId() == $personnel->getIdTypeOfProject() ? 'selected' : '' ?>>
              <?= $type_of_project->getName() ?>
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