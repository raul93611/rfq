<?php
Conexion::abrir_conexion();
$personnel = PersonnelRepository::getById(Conexion::obtener_conexion(), $_POST['id']);
Conexion::cerrar_conexion();
?>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" class="form-control form-control-sm" value="<?= $personnel->getName(); ?>">
      </div>
    </div>
  </div>
</div>
<input type="hidden" name="id_personnel" value="<?= $personnel->getId(); ?>">
<div class="modal-footer">
  <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>