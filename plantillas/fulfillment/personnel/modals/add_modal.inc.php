<?php
Conexion::abrir_conexion();
$types_of_projects = TypeOfProjectRepository::getAll(Conexion::obtener_conexion());
Conexion::cerrar_conexion();
?>

<div class="modal fade" id="add-personnel-modal" tabindex="-1" role="dialog" aria-labelledby="addPersonnelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addPersonnelModalLabel"><i class="fas fa-user-plus mr-2"></i>Add Personnel</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body user-form">
        <form id="add-personnel-form" method="post" enctype="multipart/form-data" action="">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" class="form-control" name="name" placeholder="Full name" required>
            <small class="form-text text-muted">Enter the full name of the personnel.</small>
          </div>
          <div class="form-row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="criteria">Criteria</label>
                <select name="criteria" class="form-control" id="criteria" required>
                  <option value="" disabled selected>Select criteria</option>
                  <option value="CONTRACTOR">Contractor</option>
                  <option value="PAYROLL">Payroll</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="id_type_of_project">Type</label>
                <select name="id_type_of_project" class="form-control" id="id_type_of_project" required>
                  <option value="" disabled selected>Select type</option>
                  <?php foreach ($types_of_projects as $type_of_project) : ?>
                    <option value="<?= $type_of_project->getId() ?>"><?= htmlspecialchars($type_of_project->getName(), ENT_QUOTES, 'UTF-8') ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer d-flex justify-content-end" style="gap:8px;">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          <i class="fa fa-times mr-1"></i>Cancel
        </button>
        <button type="submit" form="add-personnel-form" class="btn btn-primary btn-sm">
          <i class="fa fa-check mr-1"></i>Save
        </button>
      </div>
    </div>
  </div>
</div>
