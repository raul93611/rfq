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
        Conexion::abrir_conexion();
        ReQuoteAuditTrailRepository::display_audit_trails(Conexion::obtener_conexion(), $re_quote-> get_id());
        Conexion::cerrar_conexion();
        ?>
      </div>
    </div>
  </div>
</div>
