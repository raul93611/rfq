<?php
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
RepositorioUsuario::clear_ms_tokens($conexion, $_SESSION['user']->obtener_id());
Conexion::cerrar_conexion();

Redireccion::redirigir(MY_ACCOUNT . '?ms_disconnected=1');
