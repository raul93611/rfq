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
          if($cotizacion_recuperada-> obtener_canal() != 'Chemonics' && $cotizacion_recuperada-> obtener_canal() != 'Ebay & Amazon'){
            if($cotizacion_recuperada-> obtener_completado()){
              ?>
              <a class="btn btn-primary" href="<?php echo COPY_QUOTE . $cotizacion_recuperada-> obtener_id(); ?>"><i class="fa fa-copy"></i> Copy</a>
              <?php
            }
          }
          Conexion::abrir_conexion();
          $cantidad_de_comentarios = RepositorioComment::contar_todos_comentarios_quote(Conexion::obtener_conexion(), $cotizacion_recuperada-> obtener_id());
          Conexion::cerrar_conexion();
          ?>
          <a href="#" id="mostrar_comentarios" class="btn btn-info"><i class="fas fa-comment"></i> Comments(<?php echo $cantidad_de_comentarios; ?>)</a>
          <button id="audit_trails_button" type="button" name="button" class="btn btn-info">Audit Trails</button>
        </div>
        <div class="col-md-2">
          <?php
          if($cotizacion_recuperada-> obtener_invoice()){
            ?>
            <h1 class="float-right text-success"><i class="fa fa-check"></i> Invoice</h1>
            <?php
          }else if($cotizacion_recuperada-> obtener_fullfillment()){
            ?>
            <h1 class="float-right text-success"><i class="fa fa-check"></i> Fulfillment</h1>
            <div class="clearfix"></div>
            <a href="<?php echo REMOVE_FULFILLMENT . $cotizacion_recuperada-> obtener_id(); ?>" class=" float-right d-block"><i class="fas fa-times"></i> Remove Fulfillment</a>
            <?php
          }else if($cotizacion_recuperada-> obtener_completado() && $cotizacion_recuperada-> obtener_status() && $cotizacion_recuperada-> obtener_award()){
          ?>
            <h1 class="float-right text-success"><i class="fa fa-check"></i> Award</h1>
            <div class="clearfix"></div>
            <a href="<?php echo REMOVE_AWARD . $cotizacion_recuperada-> obtener_id(); ?>" class=" float-right d-block"><i class="fas fa-times"></i> Remove Award</a>
          <?php
          }else if($cotizacion_recuperada-> obtener_completado() && $cotizacion_recuperada-> obtener_status() && !$cotizacion_recuperada-> obtener_award()){
          ?>
            <h1 class="float-right text-success"><i class="fa fa-check"></i> Submitted</h1>
          <?php
          }else if($cotizacion_recuperada-> obtener_completado() && !$cotizacion_recuperada-> obtener_status() && !$cotizacion_recuperada-> obtener_award()){
            ?>
            <h1 class="float-right text-success"><i class="fa fa-check"></i> Completed</h1>
            <?php
          }
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
<!--*************************************************SAVE QUOTE INFO*************************************************************-->
<div class="modal fade" id="quote_info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Quote Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="quote_info_form" method="post" enctype="multipart/form-data" action="<?php echo SAVE_QUOTE_INFO; ?>">
          <?php include_once 'forms/quote/quote_info.inc.php'; ?>
          <input type="hidden" name="id_rfq" value="<?php echo $cotizacion_recuperada-> obtener_id(); ?>">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="save_quote_info" form="quote_info_form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
<!--*************************************************MODAL COMMENT*************************************************************-->
<div class="modal fade" id="nuevo_comment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add comment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_nuevo_comment" method="post" enctype="multipart/form-data" action="<?php echo GUARDAR_COMMENT; ?>">
          <div class="form-group">
            <label for="comment_rfq">Comment:</label>
            <textarea class="form-control form-control-sm" name="comment_rfq" rows="10" id="comment_rfq" autofocus></textarea>
          </div>
          <input type="hidden" name="id_rfq" value="<?php echo $cotizacion_recuperada-> obtener_id(); ?>">
          <input type="hidden" name="place" value="quote">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="guardar_comment" form="form_nuevo_comment" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
<!--*************************************************MODAL TO SHOW COMMENTS*************************************************************-->
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
        RepositorioComment::escribir_comments($cotizacion_recuperada-> obtener_id());
        ?>
      </div>
    </div>
  </div>
</div>
<!--*************************************************MODAL TO SHOW AUDIT TRAILS*************************************************************-->
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
        AuditTrailRepository::display_audit_trails(Conexion::obtener_conexion(), $cotizacion_recuperada-> obtener_id());
        Conexion::cerrar_conexion();
        ?>
      </div>
    </div>
  </div>
</div>
<!--*********************************************************ADD SERVICE*******************************************************-->
<div class="modal fade" id="add_service_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Service</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php include_once 'forms/service/add_service_form.inc.php'; ?>
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_service_button" form="add_service_form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
<!--*********************************************************EDIT SERVICE*******************************************************-->
<div class="modal fade" id="edit_service_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Service</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="" id="edit_service_form" action="<?php echo EDIT_SERVICE; ?>" method="post">

        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="edit_service_button" form="edit_service_form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo RUTA_JS; ?>services.js"></script>
<script src="<?php echo RUTA_JS; ?>quote.js"></script>
