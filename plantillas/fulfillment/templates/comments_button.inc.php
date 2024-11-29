<?php
// Open database connection
Conexion::abrir_conexion();

// Count comments related to the quote
$cantidad_de_comentarios = RepositorioComment::contar_todos_comentarios_quote(Conexion::obtener_conexion(), $quote->obtener_id());

// Close database connection
Conexion::cerrar_conexion();
?>

<!-- Button to display comments -->
<a href="#" id="mostrar_comentarios" class="btn btn-info">
  <i class="fas fa-comment"></i> Comments (<?= htmlspecialchars($cantidad_de_comentarios, ENT_QUOTES, 'UTF-8'); ?>)
</a>