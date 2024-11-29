<div class="modal fade" id="todos_commentarios_quote" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Comments</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
        // Ensure cotizacion ID is valid before trying to display comments
        $cotizacionId = $cotizacion_recuperada->obtener_id();
        if ($cotizacionId) {
          RepositorioComment::escribir_comments($cotizacionId);
        } else {
          echo "<p>Error: Unable to retrieve comments. Invalid quotation ID.</p>";
        }
        ?>
      </div>
    </div>
  </div>
</div>