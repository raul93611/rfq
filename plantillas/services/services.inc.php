<h2>Services:</h2>
<div class="row">
  <div class="col-md-12">
    <button type="button" name="button" class="btn btn-primary float-right" id="add_service">Add</button>
  </div>
</div>
<div class="row mt-4">
  <div class="col-md-12">
    <?php
    Database::open_connection();
    ServiceRepository::display_services(Database::get_connection(), $cotizacion_recuperada->obtener_id());
    Database::close_connection();
    ?>
  </div>
</div>
