<?php
if (!ControlSesion::sesion_iniciada()) {
    Redireccion::redirigir1(SERVIDOR);
}

?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>Add equipment</h1>
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
                        <form role="form" name="form_equipo" onsubmit="return validar_form()" method="post" action="<?php echo EDITAR_COTIZACION; ?>">
                            <?php
                            if(isset($_POST['registrar_equipo'])){
                                $id_rfq = $_POST['id_rfq'];
                                include_once 'plantillas/registro_equipo_vacio.inc.php';
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
