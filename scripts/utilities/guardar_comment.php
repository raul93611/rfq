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

    // Get commenter's MS tokens once — their account is used to send emails to recipients
    $commenter_tokens = RepositorioUsuario::get_ms_tokens($conexion, $commenter_id);
    $can_send_email   = !empty($commenter_tokens['ms_refresh_token']);

    // Insert notifications and optionally send email
    foreach ($recipients as $rec) {
      $rec_user = $rec['user'];
      NotificationRepository::insert($conexion, $rec_user->obtener_id(), $id_rfq, $rec['message'], $url);

      // Send via commenter's delegated MS account to recipient's email
      if ($can_send_email && !empty($rec_user->obtener_email())) {
        _send_mention_email($commenter_tokens, $rec['message'], $url, $rec_user->obtener_email(), $comment_txt);
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
 * Send an email notification using the commenter's delegated MS access token.
 * $to_email is always the recipient's address. Silently fails if token is invalid.
 */
function _send_mention_email($tokens, $message, $url, $to_email, $comment_text = '') {
  $access_token = $tokens['ms_access_token'];
  $expiry       = (int)($tokens['ms_token_expiry'] ?? 0);

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

  if (!$access_token || !$to_email) return;

  $safe_message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
  $safe_url     = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
  $safe_comment = $comment_text ? nl2br(htmlspecialchars($comment_text, ENT_QUOTES, 'UTF-8')) : '';

  $comment_block = $safe_comment ? '
        <!-- Comment -->
        <tr>
          <td style="padding:0 32px 28px;">
            <p style="margin:0 0 8px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;color:#94a3b8;font-family:\'Manrope\',Arial,sans-serif;">Comment</p>
            <div style="background:#f8fafc;border-left:3px solid #0ea5e9;border-radius:0 6px 6px 0;padding:14px 16px;font-size:14px;color:#334155;line-height:1.6;font-family:\'Manrope\',Arial,sans-serif;">' . $safe_comment . '</div>
          </td>
        </tr>' : '';

  $body_html = '<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:\'Manrope\',Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:40px 0;">
    <tr><td align="center">
      <table width="560" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.08);">

        <!-- Header -->
        <tr>
          <td style="background:#1a2332;padding:24px 32px;">
            <span style="color:#ffffff;font-size:20px;font-weight:700;letter-spacing:-0.3px;font-family:\'Manrope\',Arial,sans-serif;">E-logic Portal</span>
          </td>
        </tr>

        <!-- Blue accent bar -->
        <tr><td style="background:#0ea5e9;height:4px;font-size:0;">&nbsp;</td></tr>

        <!-- Notification title -->
        <tr>
          <td style="padding:28px 32px 20px;">
            <p style="margin:0 0 6px;font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:0.6px;font-weight:700;font-family:\'Manrope\',Arial,sans-serif;">Notification</p>
            <p style="margin:0;font-size:18px;color:#1a2332;font-weight:700;line-height:1.4;font-family:\'Manrope\',Arial,sans-serif;">' . $safe_message . '</p>
          </td>
        </tr>

        ' . $comment_block . '

        <!-- CTA -->
        <tr>
          <td style="padding:0 32px 32px;">
            <table cellpadding="0" cellspacing="0">
              <tr>
                <td style="background:#0ea5e9;border-radius:6px;">
                  <a href="' . $safe_url . '" style="display:inline-block;padding:12px 24px;color:#ffffff;font-size:14px;font-weight:600;text-decoration:none;font-family:\'Manrope\',Arial,sans-serif;">View in E-logic Portal &rarr;</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="background:#f8fafc;border-top:1px solid #e8ecf0;padding:16px 32px;">
            <p style="margin:0;font-size:12px;color:#94a3b8;font-family:\'Manrope\',Arial,sans-serif;">You are receiving this because you were mentioned or are the designated user on this proposal. This is an automated message — please do not reply.</p>
          </td>
        </tr>

      </table>
    </td></tr>
  </table>
</body>
</html>';

  $mail_payload = json_encode([
    'message' => [
      'subject' => 'E-logic: ' . $message,
      'body'    => ['contentType' => 'HTML', 'content' => $body_html],
      'toRecipients' => [['emailAddress' => ['address' => $to_email]]],
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
