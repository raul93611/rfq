<?php
Conexion::abrir_conexion();
$cantidad_de_comentarios = RepositorioComment::contar_todos_comentarios_quote(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_id());
Conexion::cerrar_conexion();
?>
<a href="#" id="mostrar_comentarios" class="btn btn-secondary">
  <i class="fas fa-comment"></i> Comments (<?= $cantidad_de_comentarios; ?>)
</a>