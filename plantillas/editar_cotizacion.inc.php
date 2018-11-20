<?php
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}
Conexion::abrir_conexion();
$cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
if(is_null($cotizacion_recuperada)){
  Redireccion::redirigir1(SERVIDOR);
}
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-4">
          <h1>Internal quote</h1>
        </div>
        <div class="col-sm-6">
          <?php
          if(!$cotizacion_recuperada-> obtener_rfp()){
            ?>
            <a class="btn btn-info" href="<?php echo CREATE_PROJECT . $cotizacion_recuperada-> obtener_id(); ?>"><i class="fas fa-plus"></i> Create project</a>
            <?php
          }
          if($cotizacion_recuperada-> obtener_completado()){
          ?>
          <a class="btn btn-primary" href="<?php echo COPY_QUOTE . $cotizacion_recuperada-> obtener_id(); ?>"><i class="fa fa-copy"></i> Copy</a>
          <?php
          }
          Conexion::abrir_conexion();
          $cantidad_de_comentarios = RepositorioComment::contar_todos_comentarios_quote(Conexion::obtener_conexion(), $cotizacion_recuperada-> obtener_id());
          Conexion::cerrar_conexion();
          ?>
          <a href="#" id="mostrar_comentarios" class="btn btn-info"><i class="fas fa-comment"></i> Comments(<?php echo $cantidad_de_comentarios; ?>)</a>
        </div>
        <div class="col-sm-2">
          <?php
          if($cotizacion_recuperada-> obtener_completado() && $cotizacion_recuperada-> obtener_status() && $cotizacion_recuperada-> obtener_award()){
          ?>
            <h1 class="float-right text-success"><i class="fa fa-check"></i> Award</h1>
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
              include_once 'plantillas/edicion_cotizacion_recuperada.inc.php';
              include_once 'plantillas/edicion_cotizacion_recuperada2.inc.php';
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
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
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="guardar_comment" form="form_nuevo_comment" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
<!--*********************************************************MODAL FULLFILLMENT*******************************************************-->
<div class="modal fade" id="fullfillment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Send to full-fillment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="fullfillment_form" method="post" enctype="multipart/form-data" action="<?php echo GUARDAR_FULLFILLMENT_FORM; ?>">
          <div class="form-group">
            <label for="fullfillment_comment">Comment:</label>
            <textarea class="form-control form-control-sm" name="fullfillment_comment" rows="10" id="fullfillment_comment" autofocus></textarea>
          </div>
          <input type="hidden" name="id_rfq" value="<?php echo $cotizacion_recuperada-> obtener_id(); ?>">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="guardar_fullfillment_form" form="fullfillment_form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
<!--*************************************************MODAL TO SHOW COMMENTS*************************************************************-->
<div class="modal fade" id="todos_commentarios_quote" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <?php
        RepositorioComment::escribir_comments($cotizacion_recuperada-> obtener_id());
        ?>
      </div>
    </div>
  </div>
</div>
