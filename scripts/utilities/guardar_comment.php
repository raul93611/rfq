<?php
if (isset($_POST['guardar_comment'])) {
  try {
    // Open the connection
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    // Create a new comment
    $comment = new Comment(
      '',
      $_POST['id_rfq'],
      $_SESSION['user']->obtener_id(),
      $_POST['comment_rfq'],
      ''
    );

    // Insert the comment into the repository
    RepositorioComment::insertar_comment($conexion, $comment);

    // Close the connection
    Conexion::cerrar_conexion();

    // Redirect based on the 'place' parameter
    $redirect_url = ($_POST['place'] == 'quote')
      ? EDITAR_COTIZACION . '/' . $_POST['id_rfq']
      : FULFILLMENT . '/' . $_POST['id_rfq'];

    Redireccion::redirigir($redirect_url);
  } catch (Exception $e) {
    // Close the connection if open
    if (isset($conexion)) {
      Conexion::cerrar_conexion();
    }

    // Print the exception message
    print "Error: " . $e->getMessage();
  }
}
