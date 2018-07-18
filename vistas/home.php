<?php
if (ControlSesion::sesion_iniciada()) {
    Redireccion::redirigir1(PERFIL);
}

include_once 'plantillas/validacion_login.inc.php';
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Inicio</title>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo PLUGINS; ?>/font-awesome/css/font-awesome.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo DIST; ?>css/adminlte.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?php echo PLUGINS; ?>iCheck/square/blue.css">
        <link rel="Shortcut Icon" href="<?php echo RUTA_IMG; ?>favicon.png" type="image/x-icon" />
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    </head>
    <body class="hold-transition login-page" style="font-family: 'Roboto', sans-serif;">
        <div class="login-box">
            <div class="login-logo">
                <img class="mb-4" src="<?php echo RUTA_IMG; ?>elogic_logo.png" alt="" width="250" height="125">
            </div>
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Please log in</p>

                    <form action="<?php echo SERVIDOR; ?>" method="post">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" name="nombre_usuario" placeholder="Username" autofocus required
                            <?php
                            if (isset($_POST['iniciar_sesion']) && isset($_POST['nombre_usuario']) && !empty($_POST['nombre_usuario'])) {
                                echo 'value="' . $_POST['nombre_usuario'] . '"';
                            }
                            ?>
                                   >
                            <span class="fa fa-user form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                            <span class="fa fa-lock form-control-feedback"></span>
                            <?php
                            if (isset($_POST['iniciar_sesion'])) {
                                $validador->mostrar_error();
                            }
                            ?>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block btn-flat" name="iniciar_sesion">Log in</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
        <!-- /.login-box -->

        <!-- jQuery -->
        <script src="<?php echo PLUGINS; ?>jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="<?php echo PLUGINS; ?>bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- iCheck -->
        <script src="<?php echo PLUGINS; ?>iCheck/icheck.min.js"></script>
    </body>
</html>
