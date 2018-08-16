<?php
if (!ControlSesion::sesion_iniciada()) {
    Redireccion::redirigir1(SERVIDOR);
}
include_once 'plantillas/validacion_cotizacion_editada.inc.php';
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-10">
                    <h1>Internal quote</h1>
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
                            <h3 class="card-title"><i class="fa fa-pencil"></i> Enter the data</h3>
                        </div>
                        <form role="form" id="form_edited_quote" method="post" enctype="multipart/form-data" action="<?php echo EDITAR_COTIZACION . '/' . $id_rfq; ?>">
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
