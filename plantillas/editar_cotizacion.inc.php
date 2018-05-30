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
                <div class="col-sm-6">
                    <h1>Editar cotizacion</h1>
                </div>
                <div class="col-sm-6">

                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Ingrese los datos</h3>
                        </div>
                        <form role="form" method="post" enctype="multipart/form-data" action="<?php echo EDITAR_COTIZACION; ?>">
                            <?php
                            if (isset($_POST['editar'])) {
                                Conexion::abrir_conexion();
                                $id_rfq = $_POST['id_rfq'];
                                $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
                                Conexion::cerrar_conexion();
                                include_once 'plantillas/edicion_cotizacion_recuperada.inc.php';
                            } else if (isset($_POST['guardar_cambios_cotizacion'])) {
                                Conexion::abrir_conexion();
                                $id_rfq = $_POST['id_rfq'];
                                $cotizacion_recuperada = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
                                Conexion::cerrar_conexion();
                                include_once 'plantillas/edicion_cotizacion_validada.inc.php';
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

