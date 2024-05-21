<?php
Conexion::abrir_conexion();
ReQuoteRepository::reload_requote(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
Redireccion::redirigir(RE_QUOTE . $id_rfq);
