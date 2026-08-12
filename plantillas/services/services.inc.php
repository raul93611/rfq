<div class="mt-4" style="display:flex;justify-content:flex-end;">
  <button type="button" class="btn btn-primary btn-sm" id="add_service">
    <i class="fas fa-plus mr-1"></i> Add Service
  </button>
</div>

<div class="mt-2" id="services_section">
  <?php
  Conexion::abrir_conexion();
  ServiceRepository::display_services(Conexion::obtener_conexion(), $cotizacion_recuperada);
  Conexion::cerrar_conexion();
  ?>
</div>
