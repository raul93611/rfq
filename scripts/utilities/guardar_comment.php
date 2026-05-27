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

    // Insert notifications and optionally send email
    foreach ($recipients as $rec) {
      $rec_user = $rec['user'];
      NotificationRepository::insert($conexion, $rec_user->obtener_id(), $id_rfq, $rec['message'], $url);

      // Try sending email via delegated MS Graph if user has tokens
      $tokens = RepositorioUsuario::get_ms_tokens($conexion, $rec_user->obtener_id());
      if (!empty($tokens['ms_refresh_token']) && !empty($tokens['ms_email'])) {
        _send_mention_email($tokens, $rec['message'], $url, $rec_user->obtener_email());
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

/**
 * Send an email notification using the recipient's delegated MS access token.
 * Silently fails if the token is expired or invalid (no retry needed here).
 */
function _send_mention_email($tokens, $message, $url, $fallback_email) {
  $access_token = $tokens['ms_access_token'];
  $expiry       = (int)($tokens['ms_token_expiry'] ?? 0);
  $ms_email     = $tokens['ms_email'] ?: $fallback_email;

  // Refresh if expired
  if (time() >= $expiry && !empty($tokens['ms_refresh_token'])) {
    $refresh_resp = @file_get_contents(
      'https://login.microsoftonline.com/' . GRAPH_TENANT_ID . '/oauth2/v2.0/token',
      false,
      stream_context_create(['http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => http_build_query([
          'client_id'     => GRAPH_CLIENT_ID,
          'client_secret' => GRAPH_CLIENT_SECRET,
          'refresh_token' => $tokens['ms_refresh_token'],
          'grant_type'    => 'refresh_token',
          'scope'         => 'openid email profile Mail.Send offline_access',
        ]),
      ]])
    );
    if ($refresh_resp) {
      $rd = json_decode($refresh_resp, true);
      if (!empty($rd['access_token'])) {
        $access_token = $rd['access_token'];
      }
    }
  }

  if (!$access_token) return;

  $site_url = SERVIDOR;
  $body_html = '<p>' . htmlspecialchars($message) . '</p>'
    . '<p><a href="' . htmlspecialchars($url) . '">View in E-logic Portal</a></p>';

  $mail_payload = json_encode([
    'message' => [
      'subject' => 'E-logic Notification: ' . $message,
      'body'    => ['contentType' => 'HTML', 'content' => $body_html],
      'toRecipients' => [['emailAddress' => ['address' => $ms_email]]],
    ],
    'saveToSentItems' => false,
  ]);

  $ctx = stream_context_create(['http' => [
    'method'  => 'POST',
    'header'  => "Authorization: Bearer {$access_token}\r\nContent-Type: application/json\r\n",
    'content' => $mail_payload,
    'ignore_errors' => true,
  ]]);
  @file_get_contents('https://graph.microsoft.com/v1.0/me/sendMail', false, $ctx);
}
