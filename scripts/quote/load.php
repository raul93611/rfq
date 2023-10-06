<?php
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id']); 
Conexion::cerrar_conexion();
?>
<div class="card-body">
  <?php include_once 'forms/quote/edicion_cotizacion_recuperada.inc.php' ?>
</div>