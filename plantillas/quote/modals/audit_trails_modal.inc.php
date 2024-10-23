<div class="modal fade" id="audit_trails_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Audit Trails</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
        // Open connection
        Conexion::abrir_conexion();
        $conexion = Conexion::obtener_conexion();
        if ($conexion) {
          AuditTrailRepository::display_audit_trails($conexion, $cotizacion_recuperada->obtener_id());
          // Close connection
          Conexion::cerrar_conexion();
        } else {
          echo "<p>Error: Unable to connect to the database.</p>";
        }
        ?>
      </div>
    </div>
  </div>
</div>