<h2>Services:</h2>

<!-- Action button row -->
<div class="row">
  <div class="col-md-12">
    <button type="button" class="btn btn-primary float-right" id="add_service">Add Service</button>
  </div>
</div>

<!-- Services display section -->
<div class="row mt-4">
  <div class="col-md-12">
    <?php
    // Open database connection
    Conexion::abrir_conexion();

    // Display services associated with the retrieved quote
    ServiceRepository::display_services(Conexion::obtener_conexion(), $cotizacion_recuperada);

    // Close database connection
    Conexion::cerrar_conexion();
    ?>
  </div>
</div>