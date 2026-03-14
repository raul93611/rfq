<div class="quote-section-header mt-4">
  <div class="quote-section-header-title">
    <i class="fas fa-concierge-bell mr-1"></i> Services
  </div>
  <div style="display:flex;gap:6px;">
    <button type="button" class="btn btn-primary btn-sm" id="add_service">
      <i class="fas fa-plus mr-1"></i> Add Service
    </button>
  </div>
</div>

<div class="mt-3">
  <?php
  Conexion::abrir_conexion();
  ServiceRepository::display_services(Conexion::obtener_conexion(), $cotizacion_recuperada);
  Conexion::cerrar_conexion();
  ?>
</div>
