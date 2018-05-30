<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="Shortcut Icon" href="<?php echo RUTA_IMG; ?>favicon.png" type="image/x-icon" />
        <?php
        if (!isset($titulo) || empty($titulo)) {
            $titulo = Inicio;
        }
        echo "<title>$titulo</title>";
        ?>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo PLUGINS; ?>font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?php echo DIST; ?>css/adminlte.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?php echo PLUGINS; ?>iCheck/flat/blue.css">
        <!-- Morris chart -->
        <link rel="stylesheet" href="<?php echo PLUGINS; ?>morris/morris.css">
        <!-- jvectormap -->
        <link rel="stylesheet" href="<?php echo PLUGINS; ?>jvectormap/jquery-jvectormap-1.2.2.css">
        <!-- Date Picker -->
        <link rel="stylesheet" href="<?php echo PLUGINS; ?>datepicker/datepicker3.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="<?php echo PLUGINS; ?>daterangepicker/daterangepicker-bs3.css">
        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="<?php echo PLUGINS; ?>bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        <!-- Google Font: Source Sans Pro -->
    </head>
    <body class="hold-transition sidebar-mini">
        <div class="wrapper">
