<?php
if (!ControlSesion::sesion_iniciada()) {
    Redireccion::redirigir1(SERVIDOR);
}
include_once 'plantillas/validacion_out_of_scope.inc.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>Add out of scope</h1>
                </div>
                <div class="col-md-6">

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
                            <h3>Enter the data</h3>
                        </div>
                        <form role="form" method="post" action="<?php echo ADD_OUT_OF_SCOPE . '/' . $id_cuestionario; ?>">
                            <?php
                            include_once 'plantillas/out_of_scope_vacio.inc.php';
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

