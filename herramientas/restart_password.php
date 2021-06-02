<?php
include_once 'plantillas/user/validacion_restart_password_form.inc.php';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link rel="stylesheet" href="<?php echo PLUGINS; ?>/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="<?php echo DIST; ?>css/adminlte.min.css">
    <link rel="stylesheet" href="<?php echo RUTA_CSS; ?>estilos.css">
    <link rel="stylesheet" href="<?php echo PLUGINS; ?>iCheck/square/blue.css">
    <link rel="Shortcut Icon" href="<?php echo RUTA_IMG; ?>eP_favicon.png" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" id="fondo" href="<?php echo RUTA_CSS; ?>fondo1.css">
    <style>
      body{
        font-family: 'Roboto', sans-serif;
        font-size: 11pt !important;
        color: #39485A !important;
      }
      .login-box{
        background-color: white !important;
        padding: 50px;
        border-radius: 30px;
        box-shadow: 0px 0px 10px #CED4DA;
        width: 25% !important;
      }
      .btn{
        border-radius: 20px !important;
        width: 200px !important;
      }
      @media only screen and (max-width: 1400px){
        .login-box{
          width: 35% !important;
        }
      }
    </style>
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <img class="mb-4" src="<?php echo RUTA_IMG; ?>eP_logo_home.png" alt="" width="60" height="38">
      </div>
      <hr>
      <div class="card-body login-card-body">
        <p class="login-box-msg" style="color: #BDC5CF !important;">Please, provide your password</p>
        <form action="<?php echo RESTART_PASSWORD . $url_secreta; ?>" method="post">
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password1" placeholder="Password" autofocus required>
            <span class="fa fa-lock form-control-feedback" style="color: #BDC5CF !important;"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password2" placeholder="Confirm password" required>
            <span class="fa fa-lock form-control-feedback" style="color: #BDC5CF !important;"></span>
            <?php
            if(isset($_POST['send'])){
              if($error){
                ?>
                <div class="alert alert-danger" role="alert">
                  Error, try again.
                </div>
                <?php
              }else{
                ?>
                <div class="alert alert-success" role="alert">
                  Successful process!.<a href="<?php echo SERVIDOR; ?>"> Log in.</a>
                </div>
                <?php
              }
            }
            ?>
          </div>

          <input type="hidden" name="url_secreta" value="<?php echo $url_secreta; ?>">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-primary btn-flat" name="send">Send</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <script src="<?php echo PLUGINS; ?>jquery/jquery.min.js"></script>
    <script src="<?php echo PLUGINS; ?>bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo PLUGINS; ?>iCheck/icheck.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        var a = 1;
        var intervalo = setInterval(function(){
          $('#fondo').attr('href', 'https://www-elogicportal.com/rfq/css/fondo' + a + '.css');
          if(a == 3){
            a = 0;
          }
          a++;
        },2000);
      });
    </script>
  </body>
</html>
