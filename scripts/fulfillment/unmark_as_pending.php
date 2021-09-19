<?php
Conexion::abrir_conexion();
RepositorioRfq::mark_unmark_as_pending(Conexion::obtener_conexion(), $id_rfq, 0);
Conexion::cerrar_conexion();
Redireccion::redirigir(FULFILLMENT . $id_rfq);
?>
