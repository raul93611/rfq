<?php
Conexion::abrir_conexion();
RepositorioRfq::restore_quote(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();

Redireccion::redirigir(DELETED);