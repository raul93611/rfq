<?php
Conexion::abrir_conexion();
RepositorioRfq::restore_quote(Conexion::obtener_conexion(), $id_rfq);
AuditTrailRepository::quote_status_audit_trail(Conexion::obtener_conexion(), 'Restored', $id_rfq);
Conexion::cerrar_conexion();

Redireccion::redirigir(DELETED);