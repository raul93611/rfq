<?php
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}
Conexion::abrir_conexion();
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
if(is_null($cotizacion_recuperada)){
  Redireccion::redirigir1(ERROR);
}
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-2">
          <h1>Proposal # <?php echo $cotizacion_recuperada-> obtener_id(); ?></h1>
        </div>
        <div class="col-md-8 text-center">
          <?php
          include_once 'plantillas/quote/templates/copy_quote_button.inc.php';
          include_once 'plantillas/quote/templates/comments_button.inc.php';
          ?>
          <button id="audit_trails_button" type="button" name="button" class="btn btn-info">Audit Trails</button>
        </div>
        <div class="col-md-2">
          <?php
          include_once 'plantillas/quote/templates/status_title.inc.php';
          ?>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-highlighter"></i> Enter the data</h3>
            </div>
            <form role="form" id="form_edited_quote" method="post" enctype="multipart/form-data" action="<?php echo GUARDAR_EDITAR_COTIZACION . $id_rfq; ?>">
              <?php
              include_once 'forms/quote/edicion_cotizacion_recuperada.inc.php';
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php
include_once 'plantillas/quote/modals/save_quote_info_modal.inc.php';
include_once 'plantillas/quote/modals/new_comment_modal.inc.php';
include_once 'plantillas/quote/modals/comments_modal.inc.php';
include_once 'plantillas/quote/modals/audit_trails_modal.inc.php';
include_once 'plantillas/services/modals/add_service_modal.inc.php';
include_once 'plantillas/services/modals/edit_service_modal.inc.php';
?>
<script src="<?php echo RUTA_JS; ?>services.js"></script>
<script src="<?php echo RUTA_JS; ?>quote.js"></script>
