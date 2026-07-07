<?php
if (isset($_POST['guardar_comment'])) {
  try {
    Conexion::abrir_conexion();
    $conexion = Conexion::obtener_conexion();

    $id_rfq      = (int)$_POST['id_rfq'];
    $comment_txt = $_POST['comment_rfq'];
    $commenter_id = $_SESSION['user']->obtener_id();
    $place        = $_POST['place'] ?? 'quote';

    // Save comment
    $comment = new Comment('', $id_rfq, $commenter_id, $comment_txt, '');
    RepositorioComment::insertar_comment($conexion, $comment);

    // Build target URL for notifications
    $url = ($place === 'quote')
      ? EDITAR_COTIZACION . '/' . $id_rfq
      : FULFILLMENT . '/' . $id_rfq;

    // Collect recipients (deduplicated)
    $recipients = [];

    // 1. @mentioned users
    $mentioned_usernames = NotificationRepository::parseMentions($comment_txt);
    foreach ($mentioned_usernames as $username) {
      $user = RepositorioUsuario::getByUsername($conexion, $username);
      if ($user && $user->obtener_id() != $commenter_id) {
        $recipients[$user->obtener_id()] = [
          'user' => $user,
          'message' => $_SESSION['user']->obtener_nombre_usuario() . ' mentioned you on '
            . ($place === 'quote' ? 'Quote' : 'Fulfillment') . ' #' . $id_rfq,
        ];
      }
    }

    // 2. Designated user of the quote
    $rfq = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
    if ($rfq) {
      $designated_id = (int)$rfq->obtener_usuario_designado();
      if ($designated_id && $designated_id != $commenter_id && !isset($recipients[$designated_id])) {
        $des_user = RepositorioUsuario::obtener_usuario_por_id($conexion, $designated_id);
        if ($des_user) {
          $recipients[$designated_id] = [
            'user' => $des_user,
            'message' => $_SESSION['user']->obtener_nombre_usuario() . ' commented on your '
              . ($place === 'quote' ? 'Quote' : 'Fulfillment') . ' #' . $id_rfq,
          ];
        }
      }
    }

    // Insert notifications and optionally send email, respecting each recipient's prefs.
    // Email is sent from the shared notification mailbox (NotificationEmail), so delivery no
    // longer depends on whether the commenter connected a personal Microsoft account.
    foreach ($recipients as $rec) {
      $rec_user = $rec['user'];
      $prefs    = RepositorioUsuario::get_notif_prefs($conexion, $rec_user->obtener_id());

      if ($prefs['notif_inapp']) {
        NotificationRepository::insert($conexion, $rec_user->obtener_id(), $id_rfq, $rec['message'], $url);
      }

      if ($prefs['notif_email'] && !empty($rec_user->obtener_email())) {
        NotificationEmail::send($conexion, $rec_user->obtener_email(), $rec['message'], $url, $comment_txt);
      }
    }

    Conexion::cerrar_conexion();

    $redirect_url = ($place === 'quote')
      ? EDITAR_COTIZACION . '/' . $id_rfq
      : FULFILLMENT . '/' . $id_rfq;

    Redireccion::redirigir($redirect_url);

  } catch (Exception $e) {
    if (isset($conexion)) Conexion::cerrar_conexion();
    print "Error: " . $e->getMessage();
  }
}
