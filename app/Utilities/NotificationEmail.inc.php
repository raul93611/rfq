<?php

/**
 * NotificationEmail
 *
 * Sends a system notification email through the shared notification mailbox
 * (see NotificationMailboxRepository). Used by @mention/comment-reply emails (send(), with
 * this class's own branded template) and by custom HTML sends like the Daily RFQ Digest
 * (sendCustom()) — the sending identity is always the one admin-connected mailbox, never
 * the acting user, so delivery no longer depends on who triggered it.
 *
 * Silent no-op when the shared mailbox isn't connected (in-app notifications still fire).
 */
class NotificationEmail {

  /**
   * @param mixed  $conexion     open PDO connection
   * @param string $to_email     recipient address
   * @param string $message      one-line notification headline (also the subject)
   * @param string $url          "View in portal" link target
   * @param string $comment_text optional comment body to quote in the email
   */
  public static function send($conexion, $to_email, $message, $url, $comment_text = '') {
    if (!$to_email) return;
    $body_html = self::buildHtml($message, $url, $comment_text);
    self::sendCustom($conexion, $to_email, 'E-logic: ' . $message, $body_html);
  }

  /**
   * Send a fully custom HTML email through the shared mailbox (e.g. the Daily RFQ Digest).
   * Same delivery path and silent no-op behavior as send() — mailbox connectivity is the
   * only gate, callers don't need to check it themselves.
   */
  public static function sendCustom($conexion, $to_email, $subject, $html_body) {
    if (!$to_email) return;

    $access_token = NotificationMailboxRepository::getAccessToken($conexion);
    if (!$access_token) return; // mailbox not connected / refresh failed — skip the email leg

    $mail_payload = json_encode([
      'message' => [
        'subject' => $subject,
        'body'    => ['contentType' => 'HTML', 'content' => $html_body],
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

  /** Branded HTML email body. Identical markup to the former per-user template. */
  private static function buildHtml($message, $url, $comment_text) {
    $safe_message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    $safe_url     = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    $safe_comment = $comment_text ? nl2br(htmlspecialchars($comment_text, ENT_QUOTES, 'UTF-8')) : '';

    $comment_block = $safe_comment ? '
        <tr>
          <td style="padding:0 32px 28px;">
            <p style="margin:0 0 8px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;color:#94a3b8;font-family:\'Manrope\',Arial,sans-serif;">Comment</p>
            <div style="background:#f8fafc;border-left:3px solid #0ea5e9;border-radius:0 6px 6px 0;padding:14px 16px;font-size:14px;color:#334155;line-height:1.6;font-family:\'Manrope\',Arial,sans-serif;">' . $safe_comment . '</div>
          </td>
        </tr>' : '';

    return '<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:\'Manrope\',Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:40px 0;">
    <tr><td align="center">
      <table width="560" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.08);">
        <tr>
          <td style="background:#1a2332;padding:24px 32px;">
            <span style="color:#ffffff;font-size:20px;font-weight:700;letter-spacing:-0.3px;font-family:\'Manrope\',Arial,sans-serif;">E-logic Portal</span>
          </td>
        </tr>
        <tr><td style="background:#0ea5e9;height:4px;font-size:0;">&nbsp;</td></tr>
        <tr>
          <td style="padding:28px 32px 20px;">
            <p style="margin:0 0 6px;font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:0.6px;font-weight:700;font-family:\'Manrope\',Arial,sans-serif;">Notification</p>
            <p style="margin:0;font-size:18px;color:#1a2332;font-weight:700;line-height:1.4;font-family:\'Manrope\',Arial,sans-serif;">' . $safe_message . '</p>
          </td>
        </tr>
        ' . $comment_block . '
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
  }
}
