<div class="modal fade" id="audit_trails_modal" tabindex="-1" role="dialog" aria-labelledby="auditTrailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="auditTrailsModalLabel">Audit Trails</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
        // Function to display audit trails
        function displayAuditTrails($re_quote_id) {
          try {
            Conexion::abrir_conexion();
            $conexion = Conexion::obtener_conexion();
            ReQuoteAuditTrailRepository::display_audit_trails($conexion, $re_quote_id);
          } catch (Exception $e) {
            echo '<div class="alert alert-danger" role="alert">Error retrieving audit trails: ' . htmlspecialchars($e->getMessage()) . '</div>';
          } finally {
            Conexion::cerrar_conexion();
          }
        }

        // Call the function to display audit trails
        displayAuditTrails($re_quote->get_id());
        ?>
      </div>
    </div>
  </div>
</div>