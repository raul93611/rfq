<?php
Conexion::abrir_conexion();
$types_of_projects = TypeOfProjectRepository::getAll(Conexion::obtener_conexion());
Conexion::cerrar_conexion();
?>
<div class="modal fade" id="add-personnel-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-personnel-form" method="post" enctype="multipart/form-data" action="">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" class="form-control form-control-sm" name="name" value="">
              </div>
              <div class="form-group">
                <label for="criteria">Criteria:</label>
                <select name="criteria" class="custom-select" id="criteria">
                  <option>CONTRACTOR</option>
                  <option>PAYROLL</option>
                </select>
              </div>
              <div class="form-group">
                <label for="id_type_of_project">Type:</label>
                <select name="id_type_of_project" class="custom-select" id="id_type_of_project">
                  <?php foreach ($types_of_projects as $key => $type_of_project) : ?>
                    <option value="<?= $type_of_project->getId() ?>"><?= $type_of_project->getName() ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
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