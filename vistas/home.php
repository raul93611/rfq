<?php
if (ControlSesion::sesion_iniciada()) Redireccion::redirigir(ALL_TASKS);
include_once 'plantillas/user/validacion_login.inc.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="Shortcut Icon" href="<?= RUTA_IMG; ?>eP_favicon.png" type="image/x-icon" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= RUTA_CSS; ?>estilos.css">
  <style>
    .login-box {
      background-color: white !important;
      padding: 50px;
      border-radius: 30px;
      box-shadow: 0px 0px 10px #CED4DA;
      width: 25% !important;
    }

    .btn {
      border-radius: 20px !important;
      width: 200px !important;
    }

    @media only screen and (max-width: 1400px) {
      .login-box {
        width: 35% !important;
      }
    }

    @media only screen and (max-width: 1000px) {
      .login-box {
        width: 95% !important;
      }
    }
  </style>
</head>

<body id="login-background" class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <img class="mb-4" src="<?= RUTA_IMG; ?>eP_logo_home.png" alt="" width="60" height="38">
    </div>
    <hr>
    <div class="card-body login-card-body">
      <p class="login-box-msg" style="color: #BDC5CF !important;">Please log in</p>
      <form action="<?= SERVIDOR; ?>" method="post">
        <div class="form-group has-feedback">
          <input type="text" class="form-control <?= isset($_POST['iniciar_sesion']) ? 'is-invalid' : ''; ?>" name="nombre_usuario" placeholder="Username" autofocus required <?= (isset($_POST['iniciar_sesion']) && isset($_POST['nombre_usuario']) && !empty($_POST['nombre_usuario'])) ? 'value="' . $_POST['nombre_usuario'] . '"' : ''; ?>>
          <span class="fa fa-user form-control-feedback" style="color: #BDC5CF !important;"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control <?= isset($_POST['iniciar_sesion']) ? 'is-invalid' : ''; ?>" name="password" placeholder="Password" required>
          <span class="fa fa-lock form-control-feedback" style="color: #BDC5CF !important;"></span>
          <?php if (isset($_POST['iniciar_sesion'])) $validador->mostrar_error(); ?>
        </div>
        <div class="row">
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary btn-flat" name="iniciar_sesion">Log in</button>
          </div>
        </div>
      </form>
      <div class="social-auth-links text-center">
        <p>- OR -</p>
        <a href="/" class="btn btn_home btn-flat">Home</a>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</body>

</html>