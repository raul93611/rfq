<?php
Conexion::abrir_conexion();
$monthly_projection = MonthlyProjectionRepository::getById(Conexion::obtener_conexion(), $_POST['id']);
Conexion::cerrar_conexion();
?>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="projected_amount">Criteria:</label>
        <input type="number" class="form-control form-control-sm" name="projected_amount" min="0" step=".01" id="projected_amount" value="<?= $monthly_projection->getProjectedAmount() ?>">
      </div>
    </div>
  </div>
</div>
<input type="hidden" name="id_monthly_projection" value="<?= $monthly_projection->getId(); ?>">
<div class="modal-footer">
  <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
</div>