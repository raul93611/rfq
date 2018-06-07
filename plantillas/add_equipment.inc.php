<?php
if (!ControlSesion::sesion_iniciada()) {
    Redireccion::redirigir1(SERVIDOR);
}
$id_rfq = $_POST['id_rfq'];
Conexion::abrir_conexion();
$cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
$canal = $cotizacion-> obtener_canal();
switch ($canal){
    case 'GSA-Buy':
        $canal = 'gsa_buy';
        break;
    case 'FedBid':
        $canal = 'fedbid';
        break;
    case 'E-mails':
        $canal = 'emails';
        break;
    case 'FindFRP':
        $canal = 'findfrp';
        break;
    case 'FBO':
        $canal = 'fbo';
        break;
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
                        <form role="form" name="form_equipo" onsubmit="return validar_form()" method="post" action="<?php echo COTIZACIONES . $canal; ?>">
                            <?php
                            if(isset($_POST['registrar_equipo'])){
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