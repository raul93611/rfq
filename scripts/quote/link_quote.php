<?php
if(isset($_POST['submit'])){
  Conexion::abrir_conexion();
  RepositorioRfq::linkQuote(Conexion::obtener_conexion(), $_POST['master'], $_POST['id_rfq']);
  Conexion::cerrar_conexion();
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq']);
}
?>
