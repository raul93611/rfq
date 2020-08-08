<h3>Services:</h3>
<div class="row">
  <div class="col-md-12">
    <button type="button" name="button" class="btn btn-primary float-right" id="add_service">Add</button>
  </div>
</div>
<div class="row mt-4">
  <div class="col-md-12">
    <?php
    Conexion::abrir_conexion();
    ServiceRepository::display_services(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_id());
    Conexion::cerrar_conexion();
    ?>
  </div>
</div>
