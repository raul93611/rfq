<?php
if(isset($_POST['send'])){
  Conexion::abrir_conexion();
  RepositorioRfq::remove_relation(Conexion::obtener_conexion(), $_POST['slave']);
  Conexion::cerrar_conexion();
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['master']);
}
?>
