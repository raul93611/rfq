<?php
/**
 * Disconnect the shared Notification Mailbox. Admin-only.
 * In-app notifications keep firing afterwards; only the email leg is skipped.
 */
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}
if (!$_SESSION['user']->is_admin()) {
  Redireccion::redirigir1(SERVIDOR);
}

Conexion::abrir_conexion();
NotificationMailboxRepository::clear(Conexion::obtener_conexion());
Conexion::cerrar_conexion();

Redireccion::redirigir(ADMIN_SETTINGS . '?mailbox_disconnected=1');
