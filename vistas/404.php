<?php
header($_SERVER['SERVER_PROTOCOL'] . '404Not Found', true, 404);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Página no encontrada</title>

        <link href="<?php echo PLUGINS; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo PLUGINS; ?>font-awesome/font-awesome.min.css" rel="stylesheet">
    </head>
    <body class="inicio">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-2">

                </div>
                <div class="col-md-8">
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="jumbotron bg-danger text-white">
                        <h1>ERROR: 404</h1>
                        <p>Page not found.</p>
                    </div>
                </div>
                <div class="col-md-2">

                </div>
            </div>
        </div>

        <script src="<?php echo PLUGINS; ?>jquery/jquery.min.js"></script>
        <script src="<?php echo PLUGINS; ?>bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo PLUGINS; ?>popper/popper.min.js"></script>
    </body>
</html>

